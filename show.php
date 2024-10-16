<?php
// Start output buffering
$id=$_GET['id'];
$query=`SELECT*FROM hospital WHERE id="$id";`;
$result=mysql_query($query,$conn);
$row=mysql_fetch_array($result);
ob_start();
?>

    <div style="display: flex; align-items: center; justify-content: space-between;">
        <div>
            <h3 style="display: inline-block;margin-left: 20px; margin-top: 20px;">
                <?php echo $data['destination']; ?> package
            </h3>
            <h6 style="display: inline-block;margin-left: 10px;"><?php echo $data['duration']; ?></h6>
        </div>
        <div>
            <?php if ($currUser && $currUser['id'] == $data['owner']['id']): ?>
                <form action="/edit/<?php echo $data['id']; ?>" style="display: inline-block;">
                    <button style="border: none; margin-right:30px; margin-top: 20px; background-color: rgb(100, 100, 255); color: white; padding: 8px 15px; border-radius: 5px;">Edit</button>
                </form>
                <form action="/<?php echo $data['id']; ?>?_method=DELETE" method="post" style="display: inline-block;">
                    <button style="border: none; margin-right:30px; margin-top: 20px; background-color: rgb(100, 100, 255); color: white; padding: 8px; border-radius: 5px;">Delete</button>
                </form>
            <?php endif; ?>
        </div>
    </div>

    <div style="margin-left: 20px;">
        <?php foreach ($data['placesIncludes'] as $element): ?>
           | <?php echo $element; ?>
        <?php endforeach; ?>
        |
    </div>

<div class="flex">
    <div>
        <img class="coverImg" src="<?php echo $data['image']; ?>" alt="image">
    </div>
    <div class="box-2">
        <div class="flex dibba" style="gap: 20px;">
            <div class="buy">
                <div class="buyIn">
                    <h2 style="display: inline-block; margin-left: 30px;">₹<?php echo $data['price']; ?></h2>
                    <p style="display: inline-block; margin-left: 5px; height: 10px; position: relative; bottom: 5px; left: 5px;">per person</p>
                </div>
                <hr style="width: 298px; position: relative; left: -20px; bottom: 3px;">
                <form action="/book/<?php echo $data['id']; ?>" style="display: flex; justify-content: center;">
                    <button class="form-control out" style="text-align: center; font-weight: bolder; color: white; background-color: rgb(214, 132, 0); width: 150px; padding: 12px; border: none; border-radius: 25px;">Book Now</button>
                </form>
            </div>
            <div class="buy">
                <div class="flex" style="gap: 55px;">
                    <img src="../icons/help-icon.svg" alt="help-icon" style="position: relative; height: 40px; top: -10px; left: -5px;">
                    <p style="font-size: 20px; font-weight: 700; position: relative; top: -5px;">Need Help?</p>
                </div>
                <p style="position: relative; top: 14px; left: 5px;">Mail us at: <br> <?php echo $data['owner']['email']; ?></p>
            </div>
        </div>
        <hr>
        <?php include 'details.php'; ?>
    </div>
</div>

<hr>

<div style="margin-left: 70px;">
    <h3>Leave a review</h3>
    <form action="/review/<?php echo $data['id']; ?>" method="post" class="needs-validation" novalidate>
        <fieldset class="starability-checkmark" style="margin-top: 25px;">
            <input type="radio" id="no-rate" class="input-no-rate" name="rating" value="0" checked aria-label="No rating." />
            <input type="radio" id="first-rate1" name="rating" value="1" />
            <label for="first-rate1" title="Terrible">1 star</label>
            <input type="radio" id="first-rate2" name="rating" value="2" />
            <label for="first-rate2" title="Not good">2 stars</label>
            <input type="radio" id="first-rate3" name="rating" value="3" />
            <label for="first-rate3" title="Average">3 stars</label>
            <input type="radio" id="first-rate4" name="rating" value="4" />
            <label for="first-rate4" title="Very good">4 stars</label>
            <input type="radio" id="first-rate5" name="rating" value="5" />
            <label for="first-rate5" title="Amazing">5 stars</label>
        </fieldset>
        <label for="reviewDes" class="form-label">Write about your experience here:</label>
        <textarea name="comment" required class="form-control" rows="5" style="resize: none; width: 50vw;"></textarea>
        <input type="submit" value="Submit" style="border: none; background-color: rgb(100, 100, 255); color: white; margin-top: 35px; margin-bottom: 12px; padding: 8px; border-radius: 5px;">
    </form>
</div>

<hr>

<h3 style="margin-left: 70px;">All reviews</h3>

<?php if (!empty($data['review'])): ?>
    <div class="row" style="padding-left: 68px; width: 100%; height: 400px; overflow-y: auto;">
        <?php foreach ($data['review'] as $element): ?>
            <div class="col-4">
                <div style="width: 75%; border-radius: 20px; border: none; background-color: gainsboro; padding-top: 20px; padding-bottom: 5px; padding-left: 25px; margin: 20px 0px;">
                    <p>@<?php echo $element['username']; ?></p>
                    <p class="starability-result" data-rating="<?php echo $element['rating']; ?>"></p>
                    <p><?php echo $element['comment']; ?></p>
                    <?php if ($currUser && $currUser['id'] == $element['owner']): ?>
                        <form action="/review/<?php echo $data['id']; ?>/<?php echo $element['id']; ?>?_method=DELETE" method="post">
                            <button style="border: none; margin-right:30px; margin-bottom: 18px; background-color: rgb(100, 100, 255); color: white; padding: 8px; border-radius: 5px;">Delete</button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <h5 style="margin-left: 70px; margin-top: 20px;">No review yet</h5>
<?php endif; ?>

<?php
// Capture the content in a variable
$content = ob_get_clean();

// Include the general layout from the same folder
include 'boilerplate.php';
?>