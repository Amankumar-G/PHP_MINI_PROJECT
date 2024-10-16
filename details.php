<div class="details">
    <div class="card mb-4">
        <div class="card-header">
            Address
        </div>
        <div class="card-body">
            <ul style="padding: 0px 20px;">
                <p class="card-text">
                                <?php echo htmlspecialchars($data['address']); ?>
                                <?php echo htmlspecialchars($data['zip_code']); ?>
                </p>
            </ul>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            Contact Details
        </div>
        <div class="card-body">
            <ul style="padding: 0px 20px;">
                <p class="card-text">              
                    Name of Receptionist is <?php echo htmlspecialchars($data['doctor_name']); ?>. <br>
                    Email of Receptionist is <?php echo htmlspecialchars($data['doctor_email']); ?>. <br>
                    Contact Number of Receptionist is <?php echo htmlspecialchars($data['doctor_contact_number']); ?>. <br>
                    Alternate Email: <?php echo htmlspecialchars($data['email']); ?>. <br>
                </p>
            </ul>
        </div>
    </div>
</div>
