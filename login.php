<% layout("/layouts/boilerplate") %>

    <div class="row mt-3">
        <h1 class="col-6 offset-3 mb-3">Login</h1>
        <div class="col-6 offset-3">
            <form action="/login" method="POST" class="needs-validation" novalidate>
                <div class="mb-3">
                    <label for="username" class="form-lable">Username</label>
                    <input name="username" type="text" placeholder="enter username" id="username" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-lable">Password</label>
                    <input name="password" type="password" placeholder="enter password" id="password" class="form-control" required>
                </div>
                <button class="btn btn-outline-dark mt-3">login</button>
            </form>
        </div>
    </div>