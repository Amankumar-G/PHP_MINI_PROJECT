<% layout("/layouts/boilerplate") %>

    <body>
        <h1>All listings</h1>
        <!-- <form method="GET" action="/listings/new">
    <button>create new listing!</button>
   </form> -->
        <div class="row row-cols-lg-3 row-cols-md-2 row-cols-sm-3">
            <% for(listings of alllistings){%>
                <a href="/listings/<%=listings._id %>" class="listing-link">
                    <div class="card listing-card col">
                        <img src="<%= listings.image %>" class="card-img-top" style="height: 20rem;"
                            alt="listing_image">
                            <div class="card-img-overlay">a</div>
                        <div class="card-body">
                            <p class="card-text">
                                <b>
                                    <%= listings.title %>
                                </b> <br>
                                &#8377; <%= listings.price.toLocaleString("en-IN") %>/night
                            </p>
                        </div>
                    </div>
                </a>
                <%}%>
        </div>
    </body>