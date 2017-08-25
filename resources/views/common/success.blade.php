@if(session()->has('success'))
    <div class="container alert alert-success">
        {{ session()->get('success') }}
    </div>
@endif