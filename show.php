<% layout("/layouts/boilerplate") %>

    <body>
        <div class="row mt-3">
            <div class="col-8 offset-2">
                <h3>
                    <%= listing.title %>
                </h3>
            </div>
            <div class="card listing-card col-6 offset-2">
                <img src="<%= listing.image %>" class="card-img-top show-img" alt="listing_image">
                <div class="card-body">
                    <p class="card-text">Owned by   <i><%=listing.owner.username %></i></p>
                    <p class="card-text">  <%= listing.description %> </p>
                        <p class="card-text">      &#8377; <%= listing.price.toLocaleString("en-IN") %></p>
                            <p class="card-text">    <%= listing.location %> </p>
                                <p class="card-text">      <%= listing.country %></p>
                    
                </div>
            </div>
            <br>
            <% if(currUser && currUser._id.equals(listing.owner._id)) { %>
            <div class="imp-btn">
                <a href="/listings/<%=listing._id %>/edit" class="btn btn-dark  col-2 offset-2 add-btn">Edit</a>
                <form method="POST" action="/listings/<%=listing._id %>?_method=DELETE">
                    <button class="btn btn-dark offset-4">Delete</button>
                </form>
            </div>
            <% } %>
            <div class="col-8 offset-2 mb-3">
                <hr>
                <% if(currUser){ %>
                <h4>Leave a review!</h4>
                <form method="POST" action="/listings/<%=listing._id %>/reviews" novalidate class="needs-validation">
                    <div class="mb-3 mt-3">
                        <label for="rating" class="form-label">Rating</label>
                        <input type="range" min="1" max="5" name="review[rating]" id="rating" class="form-control">
                    </div>
                    <div class="mt-3 mb-3">
                        <label for="comment" class="form-label">Comments</label>
                        <textarea name="review[comment]" rows="5" cols="30" id="comment" class="form-control"
                            placeholder="enter the comment" required></textarea>
                    </div>
                    <button class="btn btn-outline-dark">Submit</button>
                </form>
                <hr>
                <% } %>
                <h4>All reviews</h4>
                <div class="row">
                    <% for(review of listing.reviews){ %>
                        <div class="card col-5 ms-3 md-3">
                            <div class="card-body">
                                <h5 class="card-body">@<%=review.author.username %></h5>
                                <p class="card-text">
                                    <%=review.comment %>
                                </p>
                                <p class="card-text">
                                    <%=review.rating %> stars
                                </p>
                            </div>
                            <% if(currUser && currUser._id.equals(review.author._id)) { %>
                            <form method="POST"
                                action="/listings/<%=listing._id %>/reviews/<%=review._id%>?_method=DELETE"
                                class="mb-3">
                                <button class="btn btn-sm btn-dark">Delete</button>
                            </form>
                            <% } %>
                        </div>
                        <% } %>
                </div>
            </div>
        </div>
    </body>