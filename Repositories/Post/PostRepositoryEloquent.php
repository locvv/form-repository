<?php


    namespace Modules\Admin\Repositories\Post;

    use Carbon\Carbon;
    use Illuminate\Http\UploadedFile;
    use Modules\Admin\Entities\Post;
    use Modules\Admin\Repositories\Base\AdminRepositoryEloquent;
    use Modules\Admin\Repositories\Base\CollectionOutputInterface;
    use Modules\Admin\Repositories\Base\ObjectOutputInterface;

class PostRepositoryEloquent extends AdminRepositoryEloquent implements PostRepository
{

    public function model()
    {
        return Post::class;
    }

    public function getPost(CollectionOutputInterface $collectionOutput = null)
    {
        if ($collectionOutput) {
            $query = $this->model()::query();
            $posts = $collectionOutput->queryBuilder($query);

            return $collectionOutput->output($posts);
        }

        return Post::all();
    }

    public function createPost(PostInputInterface $postInput)
    {
        $newPostData = $postInput->fetchData();

        return $this->model()::create($newPostData);
    }

    public function findPostById($postId, ObjectOutputInterface $objectOutput = null)
    {

        if ($objectOutput) {
            $query = Post::where('id', $postId);
            $query = $objectOutput->queryBuilder($query);

            return $objectOutput->output($query);
        }

        return $this->model()::find($postId);
    }

    public function updatePostById($postId, PostInputInterface $postInput)
    {
        $post = $this->findPostById($postId);

        $updateData = $postInput->fetchData();

        if ($post) {
            $post->update($updateData);

            return $post;
        } else {
            return false;
        }
    }

    public function deletePostById($postId)
    {
        $post = $this->findPostById($postId);

        if ($post) {
            return $post->delete();
        } else {
            return false;
        }
    }

    public function getEmptyPost()
    {
        $newPost = new $this->model();
        $newPost->publish_time = Carbon::now();

        return $newPost;
    }

    public function loadOldInput($post, \Illuminate\Http\Request $request)
    {
        $fillable = $post->getFillable();
        foreach ($fillable as $field) {
            if ($request->old($field)) {
                $post->$field = $request->old($field);
            }
        }

        return $post;
    }


    public function uploadImage($postId, UploadedFile $image, PostImageInterface $postImage)
    {
        $currentPost = $this->findPostById($postId);
        if ($currentPost) {
            $currentPost->
            $image = $postImage->upload($currentPost, $image);
        }

        return false;
    }

    public function syncCategories($postId, $categoriesIdArr)
    {
        $post = $this->findPostById($postId);

        $post->categories()->sync($categoriesIdArr);

        return $post;
    }

    public function toggleStatus($postId, $statusName)
    {
        $post = $this->findPostById($postId);
        if ($post) {
            switch ($statusName) {
                case 'published':
                    $post->published = !$post->published;

                    return $post->save();
            }
        }

        return false;
    }

    public function saveEvent($postId, array $clickData)
    {
        $currentPost = Post::find($postId);

        $currentTracking = $currentPost->trackingEvents()
            ->where('event', $clickData['event'])
            ->where('session_key', $clickData['session_key'])
            ->where('date', $clickData['date'])->first();

        if ($currentTracking) {
            $currentTracking->total++;
            $currentTracking->save();
        } else {
            $clickData['total'] = 1;
            $currentTracking = $currentPost->trackingEvents()->create($clickData);
        }
        //Update total view for post
        if ($clickData['event'] == 'click') {
            $totalView = $currentPost->trackingEvents()->where('event', 'click')->count();
            $currentPost->total_view = $totalView;
            $currentPost->save();
        }

        if ($clickData['event'] == 'share') {
            $totalShare = $currentPost->trackingEvents()->where('event', 'share')->sum('total');
            $currentPost->total_share = $totalShare;
            $currentPost->save();
        }

        return $currentTracking;
    }

    public function like($postId, array $likeData)
    {
        $likeData['spot_id'] = $postId;
        $post = Post::with('likes')->where('id', $postId)->first();

        $post->total_like = count($post->likes)+1;
        $post->save();

        return $post->likes()->create([
            'user_id' => $likeData['user_id'],
        ]);
    }

    public function unLike($postId, $userId)
    {
        $post = Post::with('likes')->where('id', $postId)->first();

        $post->total_like = count($post->likes)-1;
        $post->save();

        return $post->likes()->where('user_id', $userId)->delete();
    }

    public function createComment($postId, array $commentData)
    {
        $post = Post::find($postId);
        if ($post) {
            $newPost = $post->comments()->create($commentData);
            $newPost->load('user');
            return $newPost;
        }

        return false;
    }

    public function getComments($postId)
    {
        $post = Post::find($postId);
        if ($post) {
            return $post->comments()
                ->latest()
                ->with('user')
                ->with('likes')
                ->limit(10000)->get();
        }

        return [];
    }

    public function updateOrCreateSlug($postId, $slug)
    {
        $currentPost = $this->findPostById($postId);
        if ($currentPost) {
            $currentPost->slug()->updateOrCreate([
                'slugable_id' => $currentPost->id,
            ], ['slug' => $slug]);

            return true;
        }

        return false;
    }
}
