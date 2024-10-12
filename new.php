<% layout("/layouts/boilerplate") %>

    <body>
        <br>
        <div class="row">
            <div class="col-8 offset-2">
                <h3>Create new listings!</h3>
        <form method="POST" action="/listings" novalidate class="needs-validation" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="title" class="form-lable">Title</label>
                <input name="listing[title]" type="text" placeholder="enter title" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-lable">Description</label>
                <textarea name="listing[description]" type="text"  required placeholder="enter description"
                    class="form-control"></textarea>
            </div>
           <div class="mb-3">
            <label for="image" class="form-lable">Image</label>
            <input name="listing[image]" type="file"  class="form-control">
        </div>
        <div class="row">  
                <div class="mb-3 col-4">
            <label for="price" class="form-lable">Price</label>
            <input name="listing[price]"  required placeholder="enter price"  class="form-control">
        </div>
        <div class="mb-3 col-8">
            <label for="location" class="form-lable">Country</label>
            <input name="listing[country]" type="text" required placeholder="enter country"class="form-control">
        </div>
    </div>
  
        <div class="mb-3">
            <label for="location" class="form-lable">Location</label>
            <input name="listing[location]" type="text" required placeholder="enter location"  class="form-control">
        </div>
        <br><br>
            <button class="btn btn-dark add-btn">ADD</button>
        </form>
    </div>
</div>
<br><br>
    </body>