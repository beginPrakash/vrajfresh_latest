<!DOCTYPE html>
<html>
<head>
    <title>Blog List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container my-5">
    <h2 class="mb-4 text-center">Latest Blogs</h2>
    <div class="row" id="blog_data"></div>

    <div class="text-center mt-4">
        <button class="btn btn-primary" id="load_more">Load More</button>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script>
let page = 1;

function loadBlogs() {
    var json_request = {
				"oauth_key": "F1CEC5YC4rrNhTzkP4aNR4Td3XAzCcHAWM4Eh1iDoofbl6xT",
				"page": page
			};

    $.ajax({
       url: api_url_prefix + 'get-blogs',
        type: "POST",
        data: JSON.stringify(json_request),
        dataType: "json",
        success: function(response) {
            if(response.blogs.length > 0){
                let html = '';
                $.each(response.blogs, function(i, blog){
                    html += `
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card h-100 shadow-sm">
                            <img src="<?= base_url('uploads/') ?>${blog.image}" 
                                 class="card-img-top" style="height:200px; object-fit:cover;">
                            <div class="card-body">
                                <h5>${blog.title}</h5>
                                <p>${blog.description.substring(0,100)}...</p>
                            </div>
                        </div>
                    </div>`;
                });
                $('#blog_data').append(html);
            } else {
                $('#load_more').hide();
            }
        }
    });
}

$(document).ready(function(){
    loadBlogs();

    $('#load_more').click(function(){
        page++;
        loadBlogs();
    });
});
</script>

</body>
</html>
