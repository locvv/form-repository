<!--Name Input -->
<div class="form-group">
    <label for="name">Name</label>
    <input type="text" name="name" id="name" value="{{$test->name}}" class="form-control col-md-6" id="">

</div>
<!--Phone Input -->
<div class="form-group">
    <label for="phone">Phone</label>
    <input type="text" name="phone" id="phone" value="{{$test->phone}}" class="form-control col-md-6" id="">

</div>

@push("after-scripts")
    <script type="text/javascript" src="{{ asset("vendor/jsvalidation/js/jsvalidation.js")}}"></script>
@endpush