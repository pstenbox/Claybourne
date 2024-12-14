$(document).ready(function() {
    $('.giftcard_submit_btn').click(function() {

        var firstName = $('input[name="customer_first_name"]').val().trim();
        var lastName = $('input[name="customer_last_name"]').val().trim();
        var email = $('input[name="customer_email"]').val().trim();
        var shirtSize = $('select[name="customer_shirt_size"]').val(); // New shirt size field
        var skillAnswer = $('input[name="skill_testing_answer"]').val().trim(); // New skill testing field

        // Validate all fields
        if (!firstName || !lastName || !email || !shirtSize || !skillAnswer) {
            alert('Please fill all the fields.');
            return;
        }

        // Validate skill testing answer (expected answer: 420)
        if (parseInt(skillAnswer) !== 420) {
            alert('Incorrect answer to the skill testing question.');
            return;
        }

        // Continue with AJAX request to submit user data
        $.ajax({
            url: './server/submit_data_capture.php',
            type: 'POST',
            data: {
                firstName: firstName,
                lastName: lastName,
                email: email,
                shirtSize: shirtSize, // Pass shirt size
                skillAnswer: skillAnswer, // Pass skill testing answer
                globalPin: global_pin_code
            },
            dataType: 'json',
            success: function(response) {
                if (response.isValid) {
                    alert('Submission Successful. Welcome!');
                    // showSection('.thankYou');

                    if (skipOutro) {
                        // If skipping outro, navigate to the desired next page/frame
                        togglePages('.nextPageFrame'); // Replace '.nextPageFrame' with your actual next page's class
                    } else {
                        // Show the Outro Animation
                        togglePages('.outroAnimation');
                
                        // Set the outro video to loop and play
                        $('#outroVideo').attr('loop', true).get(0).play().catch(function(error) {
                            console.error('Error playing outroVideo:', error);
                        });
                    }
                    
                    // AJAX request to store winner information in the "winners" table
                    $.ajax({
                        url: './server/store_winner_info.php',
                        type: 'POST',
                        data: {
                            firstName: firstName,
                            lastName: lastName,
                            email: email,
                            shirtSize: shirtSize, // Include shirt size in winner info
                            skillAnswer: skillAnswer, // Include skill answer in winner info
                            globalStoreName: global_store_name,
                            giftCardCode: global_pin_code,
                            suspicionLevel: 'Low',
                            approvalStatus: 'Pending'
                        },
                        dataType: 'json',
                        success: function(storeResponse) {
                            console.log('Winner information stored successfully:', storeResponse);

                            if (storeResponse.isValid) {
                                // Check the autosend status before sending an email
                                $.ajax({
                                    url: './server/check_autosend.php',  // Server script that checks the autosend value
                                    type: 'GET',
                                    dataType: 'json',
                                    success: function(autosendResponse) {
                                        if (autosendResponse.autosend) {
                                            // If autosend is true, make another AJAX call to send an email
                                            $.ajax({
                                                url: './mail/prizeEmail.php',  // Correct path to prizeEmail.php
                                                type: 'POST',
                                                data: {
                                                    cEmail: email,
                                                    cfirstName: firstName,
                                                    clastName: lastName
                                                },
                                                success: function(emailResponse) {
                                                    console.log("Email sent successfully:", emailResponse);
                                                },
                                                error: function(xhr, status, error) {
                                                    console.error("Error sending email: " + error);
                                                }
                                            });
                                        } else {
                                            console.log("Autosend is not enabled, email not sent.");
                                        }
                                    },
                                    error: function(xhr, status, error) {
                                        console.error("Error checking autosend status: " + error);
                                    }
                                });
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error("Error storing winner information: " + error);
                        }
                    });
                } else {
                    alert(response.error);
                }
            },
            error: function(xhr, status, error) {
                console.error("Error submitting user data: " + error);
                alert("Submission Failed. Please try again.");
            }
        });
    });
});
