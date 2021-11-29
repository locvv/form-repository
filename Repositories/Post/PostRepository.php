<?php

    namespace Modules\Admin\Repositories\Post;

    use Illuminate\Http\UploadedFile;
    use Modules\Admin\Repositories\Base\CollectionOutputInterface;
    use Modules\Admin\Repositories\Base\ObjectOutputInterface;
    use Prettus\Repository\Contracts\RepositoryInterface;

interface PostRepository extends RepositoryInterface
{

    public function getPost(CollectionOutputInterface $collectionOutput);

    public function createPost(PostInputInterface $postInput);

    public function findPostById($postId, ObjectOutputInterface $objectOutput = null);

    public function updatePostById($postId, PostInputInterface $postInput);

    public function deletePostById($postId);

    public function getEmptyPost();

    public function loadOldInput($post, \Illuminate\Http\Request $request);

    public function uploadImage($postId, UploadedFile $image, PostImageInterface $postImage);

    public function syncCategories($postId, $categoriesIdArr);

    public function toggleStatus($postId, $statusName);

    public function saveEvent($postId, array $clickData);

    public function like($postId, array $likeData);

    public function unLike($postId, $userId);

    public function createComment($postId, array $commentData);

    public function getComments($postId);

    public function updateOrCreateSlug($postId, $slug);
}
