var skipIntro = false; // Set this based on your application logic
var skipOutro = false; // Set this based on your application logic to skip outro

function togglePages(pageClassName) {
    // Hide all page frames
    $('.pageFrame').hide();

    // Show the specified page
    $(pageClassName).show();

    // Handle introAnimation
    if (!$(pageClassName).hasClass('introAnimation')) {
        const introVideo = $('#introVideo').get(0);
        if (introVideo) {
            introVideo.pause(); // Pause the video
            $('.introAnimation').addClass('hideIntroVideo');
            introVideo.currentTime = 0; // Reset the video to the start
        }
    }

    // Handle outroAnimation
    if (!$(pageClassName).hasClass('outroAnimation')) {
        const outroVideo = $('#outroVideo').get(0);
        if (outroVideo) {
            outroVideo.pause(); // Pause the video
            outroVideo.currentTime = 0; // Reset the video to the start
            outroVideo.loop = false; // Ensure it stops looping
        }
    } else {
        const outroVideo = $('#outroVideo').get(0);
        if (outroVideo) {
            outroVideo.loop = true; // Set the video to loop
            outroVideo.play().catch(error => {
                console.error('Error playing outroVideo:', error);
                // Optionally, you can handle the error by hiding the outro or notifying the user
            });
        }
    }
}

$(document).ready(function() {
    // Page Animations / Transitions Constants
    const FADE_OUT_DURATION = 0;
    const FADE_IN_DURATION = 0;
    const CONTENT_FADE_IN_DURATION = 1500;
    const TRANSITION_FADE_OUT_DURATION = 500;
    const TRANSITION_BEFORE_END = 2500; // Time before video ends to fade in content (in ms)

    const EL_video = document.querySelector("#globalTransitionVideo");
    const EL_transitionScene = document.querySelector("#globalTransitionScene");

    // Initially hide the transition scene
    $(EL_transitionScene).hide();

    // Unified Transition Video Function
    function playTransitionVideo(pageFrame, $button, nextPageFrame = null, onFinish = null) {
        if ($button && !$button.length) {
            console.error("Invalid button reference.");
            return;
        }

        if ($button && $button.data('clicked')) return;
        if ($button) $button.data('clicked', true);

        if (!pageFrame) {
            if ($button) $button.data('clicked', false); // Reset button if no valid page frame found
            return;
        }

        // Append the transition scene to the active page frame
        pageFrame.appendChild(EL_transitionScene);

        // Fade out all children except the transition scene
        $(pageFrame).children().not(EL_transitionScene).fadeOut(FADE_OUT_DURATION, function() {
            // Fade in the transition scene
            $(EL_transitionScene).fadeIn(FADE_IN_DURATION, function() {
                var $video = $(EL_video);
                var video = EL_video;

                // Remove any existing handlers
                $video.off('.fadeInContent');

                // Reset video to start
                video.currentTime = 0;

                // Check if the video has a source
                if (!video.currentSrc) {
                    console.warn("Transition video has no source. Skipping video playback.");
                    // Directly fade in content and proceed
                    $(pageFrame).children().not(EL_transitionScene).fadeIn(CONTENT_FADE_IN_DURATION);
                    $(EL_transitionScene).fadeOut(TRANSITION_FADE_OUT_DURATION, function() {
                        if ($button) $button.data('clicked', false); // Reset button state after fade out

                        // Execute onFinish callback if provided
                        if (typeof onFinish === 'function') {
                            onFinish();
                        }
                    });
                    return;
                }

                function startPlayback() {
                    // Remove any existing 'timeupdate' handler
                    $video.off('timeupdate.fadeInContent');

                    // Add 'timeupdate' event to check current time
                    $video.on('timeupdate.fadeInContent', function() {
                        var timeRemaining = video.duration - video.currentTime;
                        if (timeRemaining <= (TRANSITION_BEFORE_END / 1000)) {
                            fadeInContent();
                        }
                    });

                    // Play the video
                    var playPromise = video.play();
                    if (playPromise !== undefined) {
                        playPromise.catch(function(error) {
                            console.error('Error playing video:', error);
                            if ($button) $button.data('clicked', false); // Reset button state on error
                            // Proceed to fade in content even if video fails
                            fadeInContent();
                        });
                    } else {
                        if ($button) $button.data('clicked', false); // Reset button state if playback fails
                        fadeInContent();
                    }
                }

                function fadeInContent() {
                    // Remove the 'timeupdate' handler
                    $video.off('timeupdate.fadeInContent');

                    $(pageFrame).children().not(EL_transitionScene).fadeIn(CONTENT_FADE_IN_DURATION);
                    $(EL_transitionScene).fadeOut(TRANSITION_FADE_OUT_DURATION, function() {
                        if ($button) $button.data('clicked', false); // Reset button state after fade out

                        // Execute onFinish callback if provided
                        if (typeof onFinish === 'function') {
                            onFinish();
                        }
                    });
                }

                if (video.readyState >= 1) {
                    startPlayback();
                } else {
                    $video.one('loadedmetadata.fadeInContent', startPlayback);
                }
            });
        });

        // Move the transition scene to the next page frame if provided
        if (nextPageFrame) {
            nextPageFrame.appendChild(EL_transitionScene);
        }
    }

    // Function to play the smoke video for correct answer
    function playSmokeVideoForCorrectAnswer(pageFrame, callback) {
        // Use a dummy button to call playTransitionVideo without actual button interaction
        playTransitionVideo(pageFrame, null, null, callback);
    }

    // Intro Logic
    if (skipIntro) {
        // Directly go to a specific page, e.g., questionPortal
        togglePages('.ageGate');
    } else {
        // Show intro animation and handle video playback
        togglePages('.introAnimation');
        const videoElement = $('#introVideo')[0]; // Get the raw HTML video element

        // Attempt to play the video.
        var playPromise = videoElement.play();

        if (playPromise !== undefined) {
            playPromise.then(_ => {
                // Autoplay started!
            }).catch(error => {
                // Autoplay was prevented.
                // Show a "Tap to Play" overlay.
                $('#videoOverlay').show();
            });
        }

        $('#videoOverlay').on('click', function() {
            videoElement.play();
            $(this).hide();
        });

        $('#introVideo').on('ended', function() {
            $('.introAnimation').fadeOut('slow', function() {
                // Move to the next .pageFrame section after the video ends
                $('.ageGate').fadeIn('slow');
            });
        });
    }

    // Quiz Logic
    $(".introAnimation").fadeIn(1500);

    const quizData = [
        {
            question: "What is the THC range of the FROSTED FLYERS 3 pack?",
            choices: [
                `20-26%`,
                "26-32%",
                "42-48%"
            ],
            correctAnswer: 2 // Adjust if necessary
        },
        {
            question: "WHAT ARE FROSTED FLYERS INFUSED WITH?",
            choices: [
                "THCa DIAMOND FROSTED",
                "Infused with Liquid Diamonds",
                "ALL OF THE ABOVE"
            ],
            correctAnswer: 2 // Adjust if necessary
        },
        {
            question: "Claybourne frosted flyers infused inputs are premium indoor full nug flower, no trim & no shake.",
            choices: [
                "TRUE",
                "FALSE"
            ],
            correctAnswer: 0 // Adjust if necessary
        }
    ];

    let currentQuestionIndex = 0;
    let selectedAnswerIndex = null;
    let score = 0;

    const questionElement = $('.quiz_question');
    const choicesElement = $('.multiple_choice');
    const nextButton = $('.quiz_submit_btn');
    const questionCounterElement = $('.current_quiz_question');

    // Audio elements
    const idleEngineSound = new Audio('sounds/engine_idle.mp3');
    const incorrectEngineSound = new Audio('sounds/engine_choke.mp3');
    const correctEngineSound = new Audio('sounds/engine_rev.wav');
    const transitionEngineSound = new Audio('sounds/engine_transition.wav');

    /* Flag Checkpoints */
    var totalCheckpoints = 3; // Total number of checkpoints (adjustable)
    var currentCheckpoint = 0;

    // Calculate the width of the progress line and individual spacing
    var containerWidth = $('.flag-checkpoint').width();
    var checkpointWidth = $('.checkpoint').outerWidth(true);
    var spacing = (containerWidth - checkpointWidth * totalCheckpoints) / (totalCheckpoints - 1); // Calculate distance between checkpoints


    $(document).ready(function() {
        // Add click event listener for .audio_section
        $('.audio_section').on('click', function() {
            // Check if the audio is currently muted by looking at one of the audio elements
            const isMuted = idleEngineSound.muted;
    
            // Toggle the mute state for all manually created audio elements
            idleEngineSound.muted = !isMuted;
            incorrectEngineSound.muted = !isMuted;
            correctEngineSound.muted = !isMuted;
            transitionEngineSound.muted = !isMuted;
    
            // Update the text to indicate the current sound status
            const soundStatus = $(this).find('.soundStatus');
            if (soundStatus.length === 0) {
                // If no status text exists, append it
                $(this).append(`<span class="soundStatus" style="margin-left: 10px; color: red;">${isMuted ? 'Sound On' : 'Sound Off'}</span>`);
            } else {
                // If it exists, update it based on the new state
                soundStatus.text(isMuted ? 'Sound On' : 'Sound Off');
            }
        });
    });
    

    // Start idle engine sound on clicking .sellsheet_btn_confirm
    // $('.sellsheet_btn_confirm').on('click', function() {
    //     idleEngineSound.loop = true; // Loop the idle engine sound
    //     idleEngineSound.play().catch(error => {
    //         console.error('Error playing idleEngineSound:', error);
    //     });
    // });

    // Function to update the flag's position
    function updateFlagPosition() {
        var flagPosition = currentCheckpoint * (checkpointWidth + spacing) - checkpointWidth / 2;
        $('.flag').css('left', flagPosition + 'px');
    }

    function resetQuiz() {
        currentCheckpoint = 0; // Reset to step 0

        // Move the flag back to the starting position
        updateFlagPosition();

        // Remove 'active' class from all checkpoints
        $('.checkpoint').removeClass('active');

        // Optionally, you could set the first checkpoint to active
        $('.checkpoint').eq(0).addClass('active');
        
        correctEngineSound.play().catch(error => {
            console.error('Error playing correctEngineSound:', error);
        });
        currentQuestionIndex = 0;
        selectedAnswerIndex = null;
        score = 0;
        togglePages('.sellSheet');
        renderQuestion();
        $('.overlay').hide();
        $(EL_transitionScene).hide(); // Ensure transition scene is hidden
        console.log("Quiz reset: currentQuestionIndex =", currentQuestionIndex, "score =", score);
    }

    function renderQuestion() {
        const currentQuestion = quizData[currentQuestionIndex];
        questionElement.text(currentQuestion.question);
        choicesElement.empty(); // Clear previous choices

        currentQuestion.choices.forEach((choice, index) => {
            const p = $('<p>').on('click', function() {
                selectChoice($(this), index);
            });

            let img = null;
            if (choice.includes('THCa DIAMOND FROSTED')) {
                img = $('<img>').attr('src', 'images/new/diamond.svg').attr('alt', 'Diamond Frosted');
            } else if (choice.includes('Infused with Liquid Diamonds')) {
                img = $('<img>').attr('src', 'images/new/gold.svg').attr('alt', 'Gold Liquid Diamonds');
            }

            if (img) {
                p.append(img);  // Append the image first
            }

            p.append(`<span>${choice}</span>`);
            choicesElement.append(p);
        });

        questionCounterElement.text(`QUESTION (${currentQuestionIndex + 1}/${quizData.length})`);
        selectedAnswerIndex = null; // Reset selected answer for the new question
    }

    function selectChoice(p, index) {
        $('.multiple_choice p').removeClass('selected_quiz_answer');
        p.addClass('selected_quiz_answer');
        selectedAnswerIndex = index;
    }

    // Bind event delegation for .quiz_finish_btn once outside finishQuiz
    $(document).off('click', '.startOverQuiz').on('click', '.startOverQuiz', function() {
        console.log("START QUIZ OVER button clicked");
        resetQuiz();
    });

    if (selectedAnswerIndex === null) {
        $(document).off('click', '.quiz_submit_btn').on('click', '.quiz_submit_btn', function() {
            if (currentCheckpoint < totalCheckpoints) {
                currentCheckpoint++;

                // Calculate the flag's next position
                var flagPosition = currentCheckpoint * (checkpointWidth + spacing + 7) - checkpointWidth / 2;

                // Move the flag to the next checkpoint
                $('.flag').css('left', flagPosition + 'px');

                // Update checkpoint color
                $('.checkpoint').eq(currentCheckpoint).addClass('active');
            }
        });
    }


    nextButton.on('click', function () {
        console.log("User Clicked.");
        // Check the current text of the button to determine the action
        const buttonText = $(this).text();

        // if (buttonText === "NEXT QUESTION" || buttonText === "SUBMIT ANSWER") {
        //     // Move to the next question
        //     if (currentQuestionIndex < quizData.length - 1) {
        //         console.log("THIS GOT TRIGGERED.");
        //         currentQuestionIndex++;
        //         renderQuestion();
        //         $(this).text('SUBMIT ANSWER'); // Reset button text
        //     } else {
        //         finishQuiz();
        //     }
        //     return;
        // }

        // If button text is "SUBMIT ANSWER", proceed to evaluate the answer
        if (selectedAnswerIndex === null) {
            alert('Please select an answer before continuing.');
            return;
        }

        $('.multiple_choice p').css('background-color', ''); // Reset all backgrounds
        const isCorrect = quizData[currentQuestionIndex].correctAnswer === selectedAnswerIndex;
        const selectedAnswer = $('.multiple_choice p').eq(selectedAnswerIndex);

        if (isCorrect) {
            console.log("CORRECT");
            handleCorrectAnswer(selectedAnswer);

            idleEngineSound.loop = true; // Loop the idle engine sound
            idleEngineSound.play().catch(error => {
                console.error('Error playing idleEngineSound:', error);
            });
        } else {
            console.log("INCORRECT");
            handleIncorrectAnswer(selectedAnswer);
            // idleEngineSound.loop = true; // Loop the idle engine sound
            // idleEngineSound.play().catch(error => {
            //     console.error('Error playing idleEngineSound:', error);
            // });
        }

        $(this).text('NEXT QUESTION');
    });

    function handleCorrectAnswer(selectedAnswer) {
        console.log("Handling correct answer. Current score before increment:", score);
        idleEngineSound.pause();
        correctEngineSound.play().catch(error => {
            console.error('Error playing correctEngineSound:', error);
        });

        score++;
        console.log("Score after increment:", score);
        selectedAnswer.css('background-color', 'gold');

        const pageFrame = $(".pageFrame").filter(':visible')[0];
        playTransitionVideo(pageFrame, null, null, function() {
            if (currentQuestionIndex < quizData.length - 1) {
                currentQuestionIndex++;
                renderQuestion();
                nextButton.text('SUBMIT ANSWER');
                console.log("Moved to next question. currentQuestionIndex =", currentQuestionIndex);
            } else {
                finishQuiz();
                console.log("Finished quiz.");
            }
        });
    }

    function handleIncorrectAnswer(selectedAnswer) {
        console.log("Handling incorrect answer.");
        idleEngineSound.pause();
        incorrectEngineSound.play().catch(error => {
            console.error('Error playing incorrectEngineSound:', error);
        });

        selectedAnswer.css('background-color', 'red');
        $('.multiple_choice p').removeClass("selected_quiz_answer");

        $('.questionPortal').fadeOut(1000, function() {
            // transitionEngineSound.play().catch(error => {
            //     console.error('Error playing transitionEngineSound:', error);
            // });
            setTimeout(() => {
                if (currentQuestionIndex < quizData.length - 1) {
                    currentQuestionIndex++;
                    $('.questionPortal').fadeIn(1000, function() {
                        renderQuestion();
                    });
                    nextButton.text('SUBMIT ANSWER');
                    console.log("Moved to next question. currentQuestionIndex =", currentQuestionIndex);
                } else {
                    finishQuiz();
                    console.log("Finished quiz.");
                }
        
                setTimeout(() => {
                    incorrectEngineSound.pause();
                    idleEngineSound.play();
                }, 2000);
            }, 1000);
        });
    }

    function finishQuiz() {
        // Calculate the score percentage
        const scorePercentage = (score / quizData.length) * 100;

        console.log("triggered");
        // Hide the question portal and fade in the result page
        $('.questionPortal').hide(); 
        $('.quizCompletedResults').fadeIn(1000);
        // togglePages('.quizCompletedResults');

        // Handle different outcomes based on score
        if (score === 0) {
            // Case: 0/3 correct
            $('.quiz_finish_title').text("Wow, Ouch!");
            $('.quiz_finish_image').attr('src', 'images/new/0of3.gif');
            $('.quiz_finish_desc').text("You didn’t get any questions correct!");
            $('.quiz_sub_desc').text("You need all 3 CORRECT to be Entered");
            $('.quiz_sub_desc').show(); // Ensure this is visible
            $('.quiz_finish_btn').text("START QUIZ OVER");
        } else if (score === 1) {
            // Case: 1/3 correct
            $('.quiz_finish_title').text("Hmmm...");
            $('.quiz_finish_image').attr('src', 'images/new/1of3.gif');
            $('.quiz_finish_desc').text("You got 1 of 3 questions correct!");
            $('.quiz_sub_desc').text("You need all 3 CORRECT to be Entered");
            $('.quiz_sub_desc').show(); // Ensure this is visible
            $('.quiz_finish_btn').text("START QUIZ OVER");
        } else if (score === 2) {
            // Case: 2/3 correct
            $('.quiz_finish_title').text("Pretty Close!");
            $('.quiz_finish_image').attr('src', 'images/new/2of3.gif');
            $('.quiz_finish_image').attr("width","200px");
            $('.quiz_finish_desc').text("You got 2 of 3 questions correct!");
            $('.quiz_sub_desc').text("You need all 3 CORRECT to be Entered");
            $('.quiz_sub_desc').show(); // Ensure this is visible
            $('.quiz_finish_btn').text("START QUIZ OVER");
            console.log("test");
        } else if (score === 3) {

            transitionEngineSound.play();
            // Case: 3/3 correct
            $('.quiz_finish_title').text("Congratulations!");
            $('.quiz_finish_image').attr('src', 'images/new/3of3.gif');
            $('.quiz_finish_desc').text("You got all three questions correct!");
            $('.quiz_sub_desc').hide(); // Hide the sub description
            $('.quiz_finish_btn').text("ENTER THE DRAW");
            $('.quiz_finish_image').attr("width","200px");

            $('.startOverQuiz').hide();
            $('.quiz_finish_btn').show();


        }

        // Optionally remove the flag after a certain time (if you want)
        setTimeout(() => {
            $('.flagAnimation').remove(); // Uncomment if you want to remove the flag after a certain time
        }, 2000); // You can adjust this duration as needed
    }

    // Initial click handler for #submitAgeGate
    $('#submitAgeGate').on('click', function(event) {
        /* Intro Audio */
        idleEngineSound.loop = true; // Loop the idle engine sound
        idleEngineSound.play().catch(error => {
            console.error('Error playing idleEngineSound:', error);
        });

        correctEngineSound.play();

        event.preventDefault();
        var $button = $(this);
        var pageFrame = $button.closest('.pageFrame')[0];
        var nextPageFrame = document.querySelector('.educationPortal'); // Select the next page frame to append the video

        playTransitionVideo(pageFrame, $button, nextPageFrame);
    });

    // Click handler for .education_portal_submitBtn
    $('.education_portal_submitBtn').on('click', function(event) {
        $('html, body').animate({ scrollTop: 0 }, 'slow');
        togglePages('.sellSheet');
        event.preventDefault();
        var $button = $(this);
        var pageFrame = $button.closest('.pageFrame')[0];
        var nextPageFrame = document.querySelector('.sellSheet'); // Select the next page frame to append the video
        correctEngineSound.play();
        playTransitionVideo(pageFrame, $button, nextPageFrame);
    });

    // Click handler for .sellsheet_btn_confirm
    $('.sellsheet_btn_confirm').on('click', function(event) {
        $('html, body').animate({ scrollTop: 0 }, 'slow');
        event.preventDefault();
        var $button = $(this);
        var pageFrame = $button.closest('.pageFrame')[0];
        var nextPageFrame = document.querySelector('.questionPortal'); // Select the next page frame to append the video
        correctEngineSound.play();

                idleEngineSound.loop = true; // Loop the idle engine sound
        idleEngineSound.play().catch(error => {
            console.error('Error playing idleEngineSound:', error);
        });
        playTransitionVideo(pageFrame, $button, nextPageFrame);
    });

    // Click handler for .startOverQuiz
    $('.startOverQuiz').on('click', function(event) {
        $('html, body').animate({ scrollTop: 0 }, 'slow');
        event.preventDefault();
        var $button = $(this);
        var pageFrame = $button.closest('.pageFrame')[0];
        var nextPageFrame = document.querySelector('.questionPortal'); // Select the next page frame to append the video

        playTransitionVideo(pageFrame, $button, nextPageFrame);
    });

    // Expose playTransitionVideo globally if needed
    window.playTransitionVideo = playTransitionVideo;

    // Render the first question after setup
    renderQuestion(); // Initial render of the quiz
});


$(document).off('click', '.quiz_finish_btn').on('click', '.quiz_finish_btn', function () {
    const correctEngineSound = new Audio('sounds/engine_rev.wav');
    
    $('html, body').animate({ scrollTop: 0 }, 'slow');
    correctEngineSound.play();

    var $button = $(this);
    var pageFrame = $button.closest('.pageFrame')[0];
    var nextPageFrame = document.querySelector('.dataCapture'); // Select the next page frame to append the video
    
    // Start the flag animation when the button is clicked
    startFlagAnimation(function() {
        // $("#flag-animation").css("display","block");
        // After the flag animation completes, trigger togglePages
        togglePages('.dataCapture');
    });
});

    var frameCount = 54; // Total number of images (from 1000.png to 1054.png)
    var currentFrame = 1000; // Start with 1000.png
    var frameDelay = 100; // Delay between frames in milliseconds (adjust for smoothness)
    var animationTimeout;

    function preloadImages() {
        // Preload images from 1000 to 1054
        for (var i = 1000; i <= 1054; i++) {
            $('<img />').attr('src', 'images/new/flag/Comp%20' + i + '.png').appendTo('body').css('display', 'none');
        }
    }

    // Function to handle the flag animation
    function startFlagAnimation(callback) {
        currentFrame = 1000; // Reset to the start frame

        function animateFlag() {
            // Set the image src to the current frame
            $('#flag-frame').attr('src', 'images/new/flag/Comp%20' + currentFrame + '.png');
            currentFrame++;

            // Check if we've reached the last frame (1054)
            if (currentFrame > 1020) { 
                togglePages('.dataCapture');
            }
            
            if (currentFrame > 1054) {

                clearTimeout(animationTimeout);
                if (typeof callback === 'function') {
                    callback(); // Trigger the callback function when animation completes
                }
            } else {
                // Continue the animation
                animationTimeout = setTimeout(animateFlag, frameDelay);
            }
        }

        // Start the flag animation
        animateFlag();
    }

    // Preload all images to ensure smooth animation
preloadImages();
    

$(document).ready(function() {
    var animationContainer = document.getElementById('arrow-down-lottie');
    
    // Load the Lottie animation
    lottie.loadAnimation({
      container: animationContainer, // the HTML element to render the animation
      renderer: 'svg', // use 'canvas' or 'html' if needed
      loop: true, // true for continuous loop, false for only playing once
      autoplay: true, // start animation automatically
      path: 'images/new/animation.json' // path to your Lottie animation JSON file
    });
  });
  