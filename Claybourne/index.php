<?php
    require 'database/db_connect.php';

    // Get user IP address
    $userIP = $_SERVER['REMOTE_ADDR'];

    // Check if the IP is banned
    $sql = "SELECT * FROM ip_blocker WHERE ip_address = ? AND banned = 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $userIP);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Redirect to banned.php if the user is banned
        header("Location: banned.php");
        exit;
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-B7YYMF6K7F"></script>
    <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'G-B7YYMF6K7F');
    </script>
    <link rel="stylesheet" href="https://use.typekit.net/ucw2nfi.css">
    <title>ClayBourne - Quiz Portal</title>
    <!-- Linking the CSS file -->
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <main>
    <div id="flag-animation">
        <img id="flag-frame" src="images/new/flag/Comp%201000.png" alt="Flag Animation">
    </div>
    <div id="globalTransitionScene" class="transitionScene" style="display: none;">
        <video id="globalTransitionVideo" width="400" muted playsinline>
            <source src="images/new/transitionScene.mp4" type="video/mp4">
            Your browser does not support the video tag.
        </video>
    </div>

        <!-- Intro Animation -->
        <section class="pageFrame introAnimation">
            <video id="introVideo" autoplay muted playsinline>
                <source src="images/intro.mp4" type="video/mp4">
                Your browser does not support the video tag.
            </video>
            <!-- Optional: Tap to play overlay (hidden by default, shown if needed) -->
            <div id="videoOverlay" style="display: none;">Tap to Play</div>
        </section>

        <!-- Outro Animation -->
        <section class="pageFrame outroAnimation" style="display: none;">
            <video id="outroVideo" autoplay muted playsinline loop>
                <source src="images/outro.mp4" type="video/mp4"> 
                Your browser does not support the video tag.
            </video>
            <!-- Optional: Tap to play overlay (hidden by default, shown if needed) -->
            <div id="videoOverlayOutro" style="display: none;">Tap to Play</div>
        </section>

        <!-- Age Gate -->
        <section class="pageFrame ageGate">
            <!-- <div class="transitionScene">
                <video id="transitionSceneVideo" autoplay muted playsinline>
                    <source src="images/new/transitionScene.mp4" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
            </div> -->

            <!--  -->
    
            <div class="frameContent">
                <div class="audio_section">
                    <img src="images/new/audioon.svg" />
                </div>
                <!-- Logo -->
                <img src="images/logo.svg" class="main_logo"/>

                <!-- Are you old enough? -->
                <div class="age_check">
                    <h1>ARE YOU 19 OR OLDER?</h1>
                    <div class="age_check_buttons">
                        <button id="yesButton">YES</button>
                        <button id="noButton">NO</button>
                    </div>
                </div>

                <!-- Province Selection -->
                <div class="provinceChoice sectionSpacer">
                    <div class="provinceContainer">
                        <h1>PROVINCE</h1>
                        <select id="provinceSelect">
                            <!-- <option value="AB">AB</option> -->
                            <option value="BC">BC</option>
                            <option value="MB">MB</option>
                            <option value="NB">NB</option>
                            <option value="NL">NL</option>
                            <option value="NS">NS</option>
                            <option value="ON">ON</option>
                            <option value="PE">PE</option>
                            <!-- <option value="QC">QC</option> -->
                            <option value="SK">SK</option>
                            <option value="NT">NT</option>
                            <option value="NU">NU</option>
                            <option value="YT">YT</option>
                        </select>
                    </div>
                </div>

                <!-- Store Detail Information -->
                <div class="store_details sectionSpacer">
                    <h1>STORE DETAILS</h1>
                    <div class="store_details_form">
                        <input type="text" id="store_name" name="store_name" placeholder="Store Name" class="input_field_default ageGateForms"/>
                        <div id="storeNameSuggestions" class="suggestions-container"></div> <!-- Store name suggestions container -->

                        <input type="text" id="store_address" name="store_address" placeholder="Store Address" class="input_field_default ageGateForms"/>
                        <div id="addressSuggestions" class="suggestions-container"></div> <!-- Suggestions container -->

                        <input type="text" id="store_pincode" name="store_pincode" placeholder="PIN Code" class="input_field_default ageGateForms"/>
                        <img src="images/tooltip.svg" class="tooltipSelector"/>
                        <button id="submitAgeGate" class="button_selected age_gate_submit_btn">LET'S ROLL</button>
                    </div>
                </div>

                <p class="footerPrint">By entering this site you agree to our <span class="termsConditions">terms OF SERVICE and privacy policy.</span> <br /><span class="privacyPolicy privacyPolicyPageURL">VIEW FULL CONTEST RULES HERE.</span></p>
            </div>

            <!-- Pin Location Tooltip -->
            <div class="tooltipGuide">
                <span class="close_tool_tip">&times;</span>
                <h1>PIN CODE IS LOCATED IN CASE OR PROVIDED BY YOUR CLAYBOURNE REP.</h1>
            </div>
            <div class="pageFrameCheckeredFooter"></div>
        </section>

        <!-- Education Portal -->
        <section class="pageFrame educationPortal">
            <div class="frameContent">
                <div class="audio_section">
                    <img src="images/new/audioon.svg" />
                </div>
                <!-- Logo -->
                <img src="images/logo.svg" class="main_logo"/>

                <!-- Answer Following Questions -->
                <div class="quizAnswerGuide">
                    <img src="images/new/answer3questions.png" style="width: 100%;"/>
                    <img src="images/new/groupSelect.svg"  style="padding-top:1.5em;"/>
                </div>
                    <button class="button_selected default_btn education_portal_submitBtn quick-start-btn" style="font-size:18px;">QUIZ CHEAT SHEET</button>
                </div>
                <p class="footerPrint">By entering this site you agree to our <span class="termsConditions">terms OF SERVICE and privacy policy.</span> <br /><span class="privacyPolicy privacyPolicyPageURL">VIEW FULL CONTEST RULES HERE.</span></p>

            </div>
            <div class="pageFrameCheckeredFooter"></div>
        </section>

        <!-- Sell Sheet Details -->
        <section class="pageFrame sellSheet">
        <div id="arrow-down-lottie" style="width: 100px; height: 100px;"></div>
        <div class="transitionScene">
            </div>
            <div class="frameContent">
                <div class="audio_section">
                    <img src="images/new/audioon.svg" />
                </div>
                <!-- Logo -->
                <img src="images/logo.svg" class="main_logo"/>

                <!-- What is Cafe Vanilla Delight? -->
                <div class="cafe_delight_description">
                    <img src="images/new/frostedLarge.svg" />
                    <img src="images/new/coloredSelection.svg" style="margin: 1.2em 0;"/>

                    <h1><span style="font-size: 40px;">3PACK</span> <span style="color:#B19132;">THC</span> <img src="images/new/meter3pack.gif" width="60" style="margin: 0 .3em;" class="meterBar"/> <span style="font-size: 26px;">42-48%</span></h1>
                    <h1 style="margin-top:-1.5em;"><span style="font-size: 40px;">5PACK</span> <span style="color:#B19132;">THC</span> <img src="images/new/meter5pack.gif" width="60" style="margin: 0 .3em;" class="meterBar"/> <span style="font-size: 26px;">36-40%</span></h1>
                </div>

                <!-- ClayBourne Cafe Vanilla Delight Live Resin -->
                <div class="cafe_delight_description">
                    <h1 style="margin-top: .3em;display: flex;align-items: center;gap: .5em;"><img src="images/new/diamond.gif" width="60"/><span style="color:#B19132;font-size: 25px;">THCa DIAMOND FROSTED</span></h1>
                    <h1 style="margin-top:-.8em;display: flex;align-items: center;gap: .5em;"><span style="color:#B19132;font-size: 25px;">LIQUID DIAMOND INFUSED</span><img src="images/new/gold.gif" width="40"/></h1>
                </div>

                <!-- Sativa Chart -->
                <div class="sativa_chart_desc">
                   <div class="divider" style="margin: .8em 0;"></div>

                   <div class="bud-descripter">
                        <img src="images/new/weedbud.gif" width="100" />
                        <p>Made with premium
                            indoor Full Nug flower.
                            <span style="color:#B19132;">No trim, no shake,</span> not ever.
                        </p>
                   </div>
                </div>

                <button class="button_selected default_btn sellsheet_btn_confirm" style="font-weight: bold;font-size: 22px;">BRING ON THE QUIZ!</button>
                
                <p class="footerPrint">By entering this site you agree to our <span class="termsConditions">terms OF SERVICE and privacy policy.</span> <br /><span class="privacyPolicy privacyPolicyPageURL">VIEW FULL CONTEST RULES HERE.</span></p>
            </div>
            <div class="pageFrameCheckeredFooter"></div>
        </section>

        <!-- Question Portal -->
        <section class="pageFrame questionPortal">
            
            <div class="frameContent">
            <div class="audio_section">
                    <img src="images/new/audioon.svg" />
                </div>
                <!-- Logo -->
                <img src="images/logo.svg" class="main_logo"/>

                <!-- Quiz Questions -->
                <h1 class="quiz_question">Loading question...</h1>

                <!-- Quiz Choices -->
                <div class="quizChoices">
                    <div class="multiple_choice">
                        <!-- Choices will be populated here by JavaScript -->
                    </div>
                </div>

                <button class="button_selected default_btn quiz_submit_btn">SUBMIT ANSWER</button>
                <!-- <p class="current_quiz_question">Loading...</p> -->
                

            <!-- Flag Checkpoint -->
            <div class="flag-checkpoint">
                <div class="progress-line"></div>
                <div class="checkpoint active"></div>
                <div class="checkpoint"></div>
                <div class="checkpoint"></div>
                <div class="flag">
                    <img src="images/new/flag.svg" alt="Flag">
                </div>
            </div>

                <p class="footerPrint">By entering this site you agree to our <span class="termsConditions">terms and conditions</span> and <span class="privacyPolicy privacyPolicyPageURL">privacy policy</span>.<br/> <span class=" contestRulesLink">CONTEST RULES</span></p>
            </div>

            <div class="pageFrameCheckeredFooter"></div>
        </section>

        <!-- Sell Sheet Details -->
        <section class="pageFrame quizCompletedResults" style="background-size: cover;">
                    <div id="flag-animation">
                <img id="flag-frame" src="images/new/flag/Comp%201000.png" alt="Flag Animation">
            </div>
            <div class="frameContent">
                <div class="audio_section">
                    <img src="images/new/audioon.svg" />
                </div>
                <!-- Logo -->
                <img src="images/logo.svg" class="main_logo"/>

                <!-- What is Cafe Vanilla Delight? -->
                <div class="cafe_delight_descriptions">
                    <h1 style="font-family: brushsci;font-weight: 500;font-size:42px;" class="quiz_finish_title">Pretty Close!</h1>
                    <img src="images/new/2of3.png" style="margin-top: 2em;" class="quiz_finish_image"/>
                        <p style="" class="quiz_finish_desc"> You got 2 of 3 questions correct!
                    </p>
                </div>

                <!-- ClayBourne Cafe Vanilla Delight Live Resin -->
                <div class="cafe_delight_description">
                    <h1 style="margin-top: .3em;display: flex;align-items: center;gap: .5em;"><span style="font-family: 'DINBold';text-transform: uppercase;color:#B19132;font-size: 18px;" class="quiz_sub_desc">You need all 3 CORRECT to be Entered</span></h1>
                </div>

                <button class="button_selected default_btn startOverQuiz" style="font-weight: bold;font-size: 22px;">START QUIZ OVER</button>
                <button class="button_selected default_btn quiz_finish_btn" style="display:none;font-weight: bold;font-size: 22px;">START QUIZ OVER</button>
                
                <p class="footerPrint">By entering this site you agree to our <span class="termsConditions">terms OF SERVICE and privacy policy.</span> <br /><span class="privacyPolicy privacyPolicyPageURL">VIEW FULL CONTEST RULES HERE.</span></p>
            </div>
            <div class="pageFrameCheckeredFooter"></div>
        </section>

        <!-- Spinning Wheel -->
        <section class="pageFrame spintowin">
            <div class="frameContent">
            <div class="audio_section">
                    <img src="images/new/audioon.svg" />
                </div>
                <!-- Logo -->
                <img src="images/logo.svg" class="main_logo"/>

                <!-- Customer Information -->
                <div class="customer_details sectionSpacer">
                    <h1 style="margin-top:-2em;padding-bottom:1em;">SPIN FOR A CHANCE TO WIN</h1>

                    <div id="wheel-container">
                        <img id="wheel" src="images/wheel/Wheel.png" alt="Wheel">
                        <img id="arrow" src="images/wheel/Arrow.png" alt="Arrow">
                        <img id="centercap" src="images/wheel/Center.png" alt="Center Cap">
                    
                    </div>
                </div>

                <p class="footerPrint">By entering this site you agree to our <span class="termsConditions">terms and conditions</span> and <span class="privacyPolicy privacyPolicyPageURL">privacy policy</span>.<br/> <span class=" contestRulesLink">CONTEST RULES</span></p>
            </div>
            <div class="pageFrameCheckeredFooter"></div>
        </section>

        <!-- Gift Card Submission Field -->
        <section class="pageFrame dataCapture">
            <div class="frameContent">
            <div class="audio_section">
                    <img src="images/new/audioon.svg" />
                </div>
                <!-- Logo -->
                <img src="images/logo.svg" class="main_logo"/>

        <!-- Customer Information -->
        <div class="customer_details sectionSpacer">
            <h1 style="font-family: 'DINBold'; margin-bottom: .5em; font-size: 21px;">TELL US ABOUT YOURSELF</h1>

            <div class="store_details_form">
                <input type="text" name="customer_first_name" placeholder="FIRST NAME" class="input_field_default"/>
                <input type="text" name="customer_last_name" placeholder="LAST NAME" class="input_field_default"/>
                <input type="text" name="customer_email" placeholder="EMAIL" class="input_field_default"/>

                <!-- Shirt Size Dropdown -->
                <select name="customer_shirt_size" class="input_field_default dropdownSizeShirt">
                    <option value="">Select Shirt Size</option>
                    <option value="XS">XS</option>
                    <option value="S">S</option>
                    <option value="M">M</option>
                    <option value="L">L</option>
                    <option value="XL">XL</option>
                    <option value="XXL">XXL</option>
                </select>

                <!-- Skill Testing Question -->
                <input type="text" name="skill_testing_answer" placeholder="800 / 2 + 20 =" class="input_field_default skillTestingNumberValue"/>

                <button class="button_selected default_btn giftcard_submit_btn" style="font-size: 22px; font-weight: bold;">
                    ENTER THE DRAW
                </button>
            </div>
        </div>


                <!-- <img src="images/new/alwaysonthegas.svg" width="200" style="margin-top: 3em;"/> -->

                <p class="footerPrint">By entering this site you agree to our <span class="termsConditions">terms OF SERVICE and privacy policy.</span> <br /><span class="privacyPolicy privacyPolicyPageURL">VIEW FULL CONTEST RULES HERE.</span></p>
            </div>
            <div class="pageFrameCheckeredFooter"></div>
        </section>

        <!-- Thank You Post Submission -->
        <section class="pageFrame thankYou">
            <div class="frameContent">
                <!-- Logo -->
                <img src="images/logo.svg" class="main_logo"/>

                <!-- Thank You -->
                <div class="customer_details sectionSpacer">
                    <h1>SENT <br/>CHECK YOUR EMAIL!</h1>

                    <button class="button_selected default_btn how_to_earn_more_btn">HOW TO WIN MORE <br />QUIZ REWARDS?</button>
                </div>

                <p class="footerPrint">By entering this site you agree to our <span class="termsConditions">terms and conditions</span> and <span class="privacyPolicy privacyPolicyPageURL">privacy policy</span>.</p>
            </div>
            <div class="pageFrameCheckeredFooter"></div>
        </section>

        <!-- How to earn more pins -->
        <section class="pageFrame morePinsGuide">
            <div class="frameContent">
                <!-- Logo -->
                <img src="images/logo.svg" class="main_logo"/>

                <!-- How to win more pins guide -->
                <div class="pinsGuide">
                    <h2>For more chances to win, ask your ClayBourne rep to rece ive PIN codes or find PIN codes inside limited cases of ClayBourne Café Vanilla Delight.</h2>
                    <img src="images/chances.png" width="330" />
                    <img src="images/giftcardvalue.png" width="330" />
                    <h2>Available until quantities last. One prize per person.</h2>
                </div>
                <p class="footerPrint">By entering this site you agree to our <span class="termsConditions">terms and conditions</span> and <span class="privacyPolicy privacyPolicyPageURL">privacy policy</span>.<br/> <span class=" contestRulesLink">CONTEST RULES</span></p>
            </div>
            <div class="pageFrameCheckeredFooter"></div>
        </section>

        <!-- Rules -->
        <section class="pageFrame policyPages rulesPage">
            <div class="frameContent">
            <!-- Logo -->
            <img src="images/logo.svg" class="main_logo"/>

            <!-- Rules -->
            <h1>ClayBourne Café Live Resin Vanilla Delight Contest Short Form Rules</h1>

            <!-- Policies -->
            <div class="termsEntry">
                <p>Full contest rules available at <a href="https://scanlearnwin.ca/#terms">www.scanlearnwin.ca/terms</a>. Contest runs from May 1, 2024, to May 1, 2025. Scan the QR code, click URL link or visit <a href="www.scanlearnwin.ca">www.scanlearnwin.ca</a> to enter unique PIN codes to participate in the quiz. All three (3) questions in quiz must be answered accurately for a chance to win a prize. The odds of winning a prize are 90% or greater. Limit one (1) prize per person. Limit one (1) prize per unique pin. Available prizes are $10 Starbucks Gift Cards. Prizes available until quantities last. Cumulative prize value of $5,000 (500 gift cards available with a $10 nominal value). NO PURCHASE NECESSARY TO PARTICIPATE IN THE CONTEST. A purchase will not increase your odds of winning. This contest is open to participants in Canada, excluding Alberta and Quebec. To participate, you must be the age of majority as per the provincial cannabis legislation in the province or territory in Canada in which you reside. Skill testing question required.</p>
            </div>

            <button class="button_selected default_btn terms_agree_btn" style="margin-bottom: 3em;">GOT IT</button>

            </div>
            <div class="pageFrameCheckeredFooter"></div>
        </section>

        <!-- Terms of Service -->
        <section class="pageFrame policyPages termsPolicy">
            <div class="frameContent">
                <!-- Logo -->
                <img src="images/logo.svg" class="main_logo"/>

                <!-- Terms of Service -->
                <h1>TERMS OF SERVICE</h1>

                <!-- Policies -->
                <div class="termsEntry">
                    <h2>Introduction</h2>
                    <p>Last updated: April 25, 2024. These terms of service (“Terms”) govern your rights and obligations regarding your access to and use of the website scanlearnwin.ca (including any content, documentation, and functionality offered thereon) (collectively, the "Website") owned by Canopy Growth Corporation and its subsidiaries ("Canopy", "we", "us", and "our"). The affiliated license holder is Tweed Inc. These Terms constitute a fully binding agreement between Canopy and you, the user of the Website (herein referred to as “you” and “your”), and contain important information regarding your legal rights, remedies, and obligations, so please read these Terms carefully. BY ACCESSING OR USING THIS WEBSITE, YOU ACCEPT AND AGREE TO BE BOUND BY AND COMPLY WITH THESE TERMS AND OUR PRIVACY POLICY. IF YOU DO NOT AGREE TO THESE TERMS OR THE PRIVACY POLICY, YOU MAY NOT ACCESS OR USE THIS WEBSITE.</p>
                </div>

                <div class="termsEntry">
                    <h2>Updates and Modifications</h2>
                    <p>Canopy reserves the right, in its sole discretion, to revise and update these Terms from time to time without notice. The date on which these Terms were last updated will appear at the top of this page, and any and all such modifications are effective immediately upon posting. Your continued use of the Site after any such modifications constitutes your acceptance of and your agreement to be bound by the new Terms. It is your responsibility to review these Terms periodically so that you are aware of any revision to which you are bound. The Website (or any portion thereof) may be revised or deleted at any time in our sole discretion without notice. We will not be liable if for any reason all or any part of the Website is restricted to users or unavailable at any time or for any period.</p>
                </div>

                <!-- Jurisdiction -->
                <div class="termsEntry">
                    <h2>Jurisdiction</h2>
                    <p>This Website is only intended for use by residents of Canada. Your access and use of this Website shall be deemed to be provided in Ontario and subject to Ontario law and the laws of Canada applicable therein. If you access this Website from outside of Canada, you do so at your own risk and are responsible for compliance with local, national and international laws, including, without limitation, import and export laws. In particular, you understand that this Website may not be available in all jurisdictions and that you are responsible for ensuring that it is lawful for you to use this Website in your jurisdiction. If you are residing in a jurisdiction where it is forbidden by law to participate in the activities offered by or related to this Website, you may not: (i) enter into these Terms; or (ii) access or use this Website. By accessing or using this Website you are explicitly stating that you have verified in your own jurisdiction that your access and use of this Website is permitted.</p>
                </div>

                <!-- Use of Website -->
                <div class="termsEntry">
                    <h2>Use of this Website</h2>
                    <p>You may only access and use this Website for lawful purposes and not for any illegal or unauthorized purpose, including without limitation, in violation of any criminal law, intellectual property law, privacy law or any other applicable law or regulation. You represent and warrant that you are at least the age of majority in your jurisdiction of residence, are legally capable of entering into a binding contract and are a person that is authorized to produce, sell or distribute cannabis. In addition, and without limitation to the foregoing, the following standards and restrictions apply to your access and use of the Website:</p>
                    <ul>
                        <li>(a) You will not copy, reproduce, modify, alter, translate, adapt, reverse engineer, disassemble, decompile, decode, hack, attempt to derive or gain access to the source code of, or create derivative works or improvements of, the Website, or any features or functionality thereof.</li>
                        <li>(b) You will not rent, lease, lend, sell, sublicense, assign, distribute, publish, transfer, publicly display, perform, transmit, stream, broadcast, or make available, the Website, or any features or functionality thereof, to any third party for any reason.</li>
                        <li>(c) You will not remove, delete, alter, obscure, any copyright, trademark, patent, brand element, or other intellectual property or proprietary rights notices provided on the Website.</li>
                        <li>(d) You will not use the Website for purposes of competitive analysis, the development of competing products or services to those offered by Canopy, copying and/or exploiting ideas, features, or functions of the Website, or any other purpose that is to our disadvantage.</li>
                        <li>(e) You will not cause or launch any programs or scripts for the purpose of scraping, indexing, surveying, or otherwise data mining any portion of the Website, or unduly burdening or hindering the operation and/or functionality of any aspect of the Website.</li>
                        <li>(f) You will not introduce or distribute any viruses, trojan horses, worms, logic bombs, or other material that is malicious or technologically harmful.</li>
                        <li>(g) You will not attempt to circumvent and/or violate the security of the Website, or otherwise gain unauthorized access to or impair any aspect of the Website, or its related systems or networks.</li>
                        <li>(h) You will not impersonate, attempt to impersonate, or otherwise misrepresent your affiliation with Canopy, a Canopy employee, another user, or any other person or entity (including, without limitation, by using email addresses, associated with any of the foregoing).</li>
                        <li>(i) You will not transmit, or procure the sending of, any advertisements, promotions, “spam”, “junk mail”, “chain letters”, or any other similar solicitation through the Website.</li>
                        <li>(j) You will not provide or contribute any false, inaccurate, or misleading information.</li>
                        <li>(k) You will not engage in any other conduct that restricts or inhibits anyone’s use or enjoyment of the Website, or which, as determined by us, may harm Canopy or users of the Website or expose them to liability.</li>
                        <li>(l) You will not promote or engage in any illegal activity, or advocate, promote, or assist any unlawful act.</li>
                        <li>(m) You will not, in any manner, violate any applicable federal, provincial, state, local, or international law or regulation including, without limitation, any laws regarding the export of data or software, patent, trademark, trade secret, copyright, or other intellectual property, legal rights (including the rights of publicity and privacy of others) or use or access the Website in a manner that could give rise to any civil or criminal liability under applicable laws or regulations or that otherwise may be in conflict with these Terms and our Privacy Policy.</li>
                    </ul>
                </div>

                <!-- Intellectual Property -->
                <div class="termsEntry">
                    <h2>Intellectual Property</h2>
                    <p>You understand and agree that the Website and its entire contents, features, and functionality, including but not limited to all information, software, code, text, displays, graphics, photographs, video, audio, design, presentation, section, and arrangement are owned by Canopy, its licensors, or other providers of such material and are protected in all forms by intellectual property laws, including without limitation copyright law, trademark law, patent law and other laws of Canada and other applicable jurisdictions. Canopy Growth Corporation, ClayBourne, and all related names, logos, product and service names, designs, images, and slogans are trademarks of Canopy or its licensors. You shall not use such marks without the prior written permission of Canopy. By using the Website, you agree to respect all copyright, trademark, and other legal notices, information, and restrictions contained in any of the content that we provide throughout the Website. We do not grant you a right or license to reproduce or create derivative works of the Website or any portion thereof, for any reason.</p>
                </div>

                <!-- Privacy -->
                <div class="termsEntry">
                    <h2>Privacy</h2>
                    <p>We are committed to respecting the privacy of the personal information of the individuals with whom we interact. For information on Canopy’s practices relating to the collection, use, and disclosure of the personal information of those individuals who access or use this Website, please review our Privacy Policy www.scanlearnwin.ca/privacypolicy.</p>
                </div>

                <!-- Disclaimer of Warranties and Limitations of Liability -->
                <div class="termsEntry">
                    <h2>Disclaimer of Warranties and Limitations of Liability</h2>
                    <p>PLEASE READ THIS SECTION CAREFULLY. THIS SECTION LIMITS THE LIABILITY OF CANOPY AND ITS AFFILIATES. EACH OF THE SUBSECTIONS BELOW APPLIES UP TO THE MAXIMUM EXTENT PERMITTED UNDER APPLICABLE LAW.</p>
                    <ul>
                        <li>(a) "AS-IS" BASIS. THE WEBSITE IS PROVIDED ON AN "AS IS," "AS AVAILABLE," “WITH ALL FAULTS AND DEFECTS” BASIS. YOUR USE OF THE WEBSITE IS AT YOUR OWN DISCRETION AND RISK.</li>
                        <li>(b) NO WARRANTIES. CANOPY DISCLAIMS ALL REPRESENTATIONS AND WARRANTIES, EXPRESS, IMPLIED OR STATUTORY, NOT EXPRESSLY SET OUT IN THESE TERMS, INCLUDING, BUT NOT LIMITED TO, ANY IMPLIED WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE, AND NON-INFRINGEMENT. IN ADDITION, CANOPY DOES NOT MAKE ANY REPRESENTATION, WARRANTY OR GUARANTEE WITH RESPECT TO THE COMPLETENESS, SECURITY, RELIABILITY, SUITABILITY, ACCURACY, CURRENCY OR AVAILABILITY OF THE WEBSITE. WITHOUT LIMITING THE FOREGOING, CANOPY DOES NOT REPRESENT OR WARRANT THAT THE WEBSITE WILL BE ACCURATE, RELIABLE, ERROR-FREE, OR UNINTERRUPTED, THAT DEFECTS WILL BE CORRECTED, OR THAT THE WEBSITE OR THE SERVER THAT MAKES IT AVAILABLE ARE FREE OF VIRUSES OR OTHER HARMFUL COMPONENTS.</li>
                        <li>(c) LIMITATION OF LIABILITY. IN NO EVENT WILL CANOPY, ITS AFFILIATES OR THEIR RESPECTIVE DIRECTORS, OFFICERS, EMPLOYEES, SECURITY HOLDERS, PARTNERS OR AGENTS (COLLECTIVELY, THE “CANOPY PARTIES”) BE LIABLE FOR ANY: (A) INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, PUNITIVE, CONSEQUENTIAL OR OTHER SIMILAR DAMAGES WHATSOEVER (EVEN IF ANY OF THE CANOPY PARTIES IS MADE AWARE OF THE POSSIBILITY OF ANY SUCH DAMAGES); OR (B) ANY DAMAGES FOR LOSS OF REVENUE, LOSS OF PROFITS, LOSS OF BUSINESS OR ANTICIPATED SAVINGS, LOSS OF USE, LOSS OF GOODWILL, LOSS OF DATA, LOSS OF OPPORTUNITY IN CONNECTION WITH OR RELATED TO ANY CLAIM, LOSS, DAMAGE, ACTION, SUIT OR OTHER PROCEEDING ARISING FROM, RELATED TO, OR IN CONNECTION WITH YOUR ACCESS, USE, OR RELIANCE ON THE WEBSITE, YOUR INABILITY TO ACCESS OR USE THE WEBSITE, OR ANY CLAIM OR CONTROVERY THAT MAY ARISE FROM ANY DISPUTES BETWEEN YOU AND OTHER USERS, WHETHER THE CLAIM IS BASED IN CONTRACT, TORT (INCLUDING NEGLIGENCE), STATUTE, OR ANY OTHER LEGAL OR EQUITABLE THEORY.</li>
                        <li>(d) MAXIMUM LIABILITY. WITHOUT LIMITING ANY OTHER PROVISION IN THESE TERMS, IN NO EVENT WILL THE CANOPY PARTIES’ TOTAL MAXIMUM AGGREGATE LIABILITY TO YOU FOR LOSSES OR DAMAGES YOU SUFFER IN CONNECTION WITH THE WEBSITE OR THESE TERMS IS LIMITED TO TWENTY DOLLARS ($20.00) (CAD).</li>
                        <li>(e) SOLE AND EXCLUSIVE REMEDY. YOUR ONLY RIGHT AND REMEDY IN CASE OF DISSATISFACTION WITH THE WEBSITE OR ANY OTHER GRIEVANCE SHALL BE YOUR AND DISCONTINUATION OF ACCESS TO, OR USE OF THE WEBSITE.</li>
                    </ul>
                </div>

                <!-- Indemnification -->
                <div class="termsEntry">
                    <h2>Indemnification</h2>
                    <p>TO THE MAXIMUM EXTENT PERMITTED BY APPLICABLE LAW, YOU AGREE TO DEFEND, INDEMNIFY, AND HOLD HARMLESS THE CANOPY PARTIES FROM AND AGAINST ANY CLAIMS, DEMANDS, LIABILITIES, DAMAGES, JUDGMENTS, AWARDS, LOSSES, COSTS, EXPENSES, OR FEES (INCLUDING LEGAL FEES) ARISING OUT OF OR IN CONNECTION WITH: (I) YOUR USE OR MISUSE OF, OR INABILITY TO USE THE WEBSITE; (II) YOUR BREACH OR VIOLATION OF THESE TERMS OR THE PRIVACY POLICY; (III) YOUR VIOLATION OF THE RIGHTS OF ANOTHER PERSON OR ENTITY; OR (IV) YOUR VIOLATION OF ANY APPLICABLE LAW. YOU AGREE THAT YOU WILL COOPERATE AS REASONABLY REQUESTED BY THE CANOPY PARTIES IN THE DEFENCE OF SUCH CLAIMS. THE CANOPY PARTIES RESERVE THE RIGHT TO ASSUME THE EXCLUSIVE CONTROL OF ANY MATTER OTHERWISE SUBJECT TO INDEMNIFICATION BY YOU.</p>
                </div>

                <!-- Termination and Suspension -->
                <div class="termsEntry">
                    <h2>Termination and Suspension</h2>
                    <p>Canopy may, at its sole discretion, for any reason or no reason, at any time and from time to time, with or without notice, terminate or suspend your access to and ability to use this Website. Upon termination or suspension, you shall immediately cease and desist from all use of this Website.</p>
                </div>

                <!-- Governing Law and Disputes -->
                <div class="termsEntry">
                    <h2>Governing Law and Disputes</h2>
                    <p>These Terms and the Privacy Policy (and all other rules, policies, or guidelines incorporated by reference) are governed by and construed in accordance with the laws of the Province of Ontario and the federal laws of Canada applicable therein, without giving effect to any common law or statutory principles of choice or conflicts of law. You expressly agree that any claims arising out of, related to, or in connection with these Terms, the Website, or your access, use, or reliance on any of the foregoing (a “Dispute”) shall be filed in a court in the City of Toronto, Ontario and you irrevocably attorn to the exclusive jurisdiction of that court. Notwithstanding the foregoing, either party may apply to any court of competent jurisdiction to obtain injunctive or other emergency or similar relief. Except where prohibited by applicable law, you agree to waive any right you may have to commence or participate in any class action against the Canopy Parties relating to any Dispute and you also agree to opt out of any such class proceedings against Canopy.</p>
                </div>

                <!-- General -->
                <div class="termsEntry">
                    <h2>General</h2>
                    <p>
                        <ul>
                            <li>(a) Entire Agreement. These Terms and the Privacy Policy (and all other rules, policies, or guidelines incorporated by reference) contain the entire agreement between you and Canopy with respect to their subject matter, and replace and supersede any prior or contemporaneous understandings, agreements, representations, warranties, and undertakings, whether written or oral, with respect to such subject matter.</li>
                            <li>(b) Waiver. No waiver by us of any of the provisions in these Terms is effective unless it is communicated to you in writing. Our failure to exercise, or our delay in exercising any right or provision in these Terms does not constitute a waiver of such right or provision. A waiver by us of any default does not constitute a waiver of any subsequent default.</li>
                            <li>(c) Number & Gender. Wherever appropriate, words importing the singular number include the plural and vice versa, words importing any gender include all genders, and words importing persons include all entities.</li>
                            <li>(d) Headings. Section headings are for convenience only and do not affect the interpretation of these Terms.</li>
                            <li>(e) Severability. If any provision of these Terms is held unenforceable, then such provision will be modified to reflect the parties’ intention, and all remaining provisions of these Terms shall remain in full force and effect.</li>
                            <li>(f) Assignment. These Terms are not assignable, transferable or sub-licensable by you except with Canopy’s prior written consent. We may assign, transfer or convey these Terms, or any of our rights hereunder to a third party without notice to you.</li>
                            <li>(g) Language. The parties require that these Terms and any related documents be drawn up in the English language.</li>
                            <li>(h) Survival. All provisions that by their nature survive expiration or termination of these Terms shall so survive.</li>
                            <li>(i) Contact Information. If you need to contact us regarding this Website or these Terms, please contact us by e-mail at marketing@canopygrowth.com</li>
                        </ul>
                    </p>
                </div>
                <button class="button_selected default_btn terms_agree_btn" style="margin-bottom: 3em;">GOT IT</button>

            </div>
            <div class="pageFrameCheckeredFooter"></div>
        </section>

        <!-- Privacy Policy -->
 

<!-- Contest Rules -->
<section class="pageFrame policyPages privacyPolicy privacyPolicyTab">
    <div class="frameContent">
        <!-- Logo -->
        <img src="images/logo.svg" class="main_logo" />

        <!-- Contest Rules -->
        <h1>OFFICIAL CONTEST RULES</h1>

        <p>
            NO PURCHASE IS NECESSARY TO ENTER OR WIN. THIS CONTEST IS GOVERNED BY CANADIAN LAW AND THE LAWS OF THE PROVINCE OF ONTARIO. SEE BELOW FOR FULL CONTEST DETAILS. PARTICIPATION IN THIS CONTEST CONSTITUTES YOUR FULL AND UNCONDITIONAL ACCEPTANCE OF, AND AGREEMENT TO BE LEGALLY BOUND BY, THESE OFFICIAL CONTEST RULES (“CONTEST RULES”).
        </p>

        <!-- Sponsor -->
        <div class="termsEntry">
            <h2>SPONSOR</h2>
            <p>The Claybourne Scan. Learn. Win. Contest (the “Contest”) is sponsored by Canopy Growth Corporation at 1 Hershey Drive, Smiths Falls, Ontario K7A 0A8 (the “Sponsor”).</p>
        </div>

        <!-- Eligibility -->
        <div class="termsEntry">
            <h2>ELIGIBILITY</h2>
            <p>The Contest is open to employees of licensed Canadian cannabis retailers who (i) are legal residents of Canada, excluding Quebec, and (ii) who have reached the legal age of majority as per the provincial cannabis legislation in the province or territory in Canada in which they reside, with the exception of employees of the Sponsor, its parent, related and affiliated companies, subsidiaries, departments or agencies, franchisees, suppliers, advertising and promotional agencies, contest administrators, and any other parties engaged in the development, production or distribution of Contest materials and those living in the same household. By participating in this Contest, entrants acknowledge compliance with, and agree to be bound by, these Contest Rules. Entrants who do not comply with any of the Official Contest Rules are subject to disqualification by the Sponsor, in the Sponsor’s sole discretion. 
</p>
        </div>

        <!-- Contest Dates and Times -->
        <div class="termsEntry">
            <h2>CONTEST DATES AND TIMES</h2>
            <p>The Contest begins on November 1st, 2024 at 12:00 am ET and ends on February 14th, 2025 at 11:59 pm ET. (the “Contest Period”), after which time the Contest will be closed and no further entries shall be accepted.</p>
        </div>

        <!-- Agreement to Be Legally Bound By Contest Rules -->
        <div class="termsEntry">
            <h2>AGREEMENT TO BE LEGALLY BOUND BY CONTEST RULES</h2>
            <p>By participating in this Contest, you are signifying that you have read and agree to be legally bound by these Contest Rules.</p>
        </div>

        <!-- How to Enter -->
        <div class="termsEntry">
            <h2>HOW TO ENTER</h2>
            <p>
               NO PURCHASE NECESSARY. During the Contest Period, eligible persons may enter the Contest by visiting www.scanlearnwin.ca/claybourne and following the steps outlined below:
                <br />
               1. Enter the unique pin code to access the quiz; and <br />
               2. Participate in the quiz and correctly answer all three (3) questions.
After following the steps above, if all questions are answered correctly, participants will receive one (1) entry. Limit of one (1) prize per person and one (1) entry per unique pin code.
The entrant must submit their entry personally. By entering the Contest, entrants fully and unconditionally agree to be bound by these Contest Rules and the decisions of the Sponsor, which will be final and binding in all matters relating to the Contest. Any attempt or suspected attempt to use robotic, automatic, programmed, or otherwise, illicit means to enter the Contest, or any other methods not authorized by these Contest Rules, for example (but not limited to) creating multiple identities, all in the Sponsor’s sole discretion, shall be deemed as tampering and may disqualify you from entering, participating and/or winning a prize. Entries that contain false or incomplete information are void. Entries that are late, lost, stolen, illegible, contain false information, are damaged, misdirected, mutilated, garbled or incomplete, altered or otherwise irregular or that do not conform with or satisfy any or all of the conditions of the Contest Rules are void. Entrants grant Sponsor a non-exclusive license to use all entries for any purpose. No correspondence will be entered into except with Selected Entrants (as defined below). Proof of transmission (screenshots or captures, etc.) does not constitute proof of entry or receipt of an entry.


            </p>
        </div>

        <!-- Contest Prizes -->
        <div class="termsEntry">
            <h2>CONTEST PRIZES</h2>
            <p>There are five (5) Prizes to be won during the Contest Period. Prizes are available while quantities last. Each “Prize” is a branded Claybourne™ jacket. The winners assume any and all liability for any injury or damage caused, or claimed to be caused, by entering, participating in this promotion or use or redemption of the Prize. Prize must be accepted as awarded. Sponsor reserves the right to substitute an alternate prize of equal or greater value if the advertised Prize is unavailable at time of award. Under no circumstances whatsoever will any difference between the actual and approximate retail values of a Prize be awarded. The Prize cannot be sold or transferred to a third party or be exchanged in whole or in part for cash. In the event that the winner is unable to claim the Prize as awarded, no compensation or substitution will be granted by the Sponsor or their subsidiaries and suppliers or by any other party associated with the Prize.</p>
        </div>

        <!-- Odds of Winning -->
        <div class="termsEntry">
            <h2>ODDS OF WINNING</h2>
            <p>The odds of winning depend on the number of eligible entries received during the Contest Period.</p>
        </div>

        <!-- Skill-Testing Question -->
        <div class="termsEntry">
            <h2>SKILL TESTING QUESTION</h2>
            <p>elected Entrants (as defined below) will be required, as a condition to winning a Prize, to correctly answer, without assistance of any kind, the Sponsor’s time-limited, mathematical skill-testing question to be administered via the Winner Release Form (as defined below).
</p>
        </div>

        <!-- Winner Selection and Confirmation -->
        <div class="termsEntry">
            <h2>WINNER SELECTION AND CONFIRMATION</h2>
            <p>On the Draw Date (as defined in Schedule A), the Sponsor, or an employee, agent, or other representative of the Sponsor, will conduct a random draw from all eligible entries and select the potential winners (the “Selected Entrant”).

The Selected Entrants will be notified within two (2) business days of the draw and will be contacted via the email address provided at the time of entry into the Contest, in Sponsor’s sole discretion, and up to two (2) attempts will be made within forty-eight (48) hours following the first notification. Sponsor is not responsible for the failure for any reason whatsoever of a Selected Entrant to receive notification or for the Sponsor to receive a Selected Entrant’s responses. If a Selected Entrant cannot be contacted within the allotted time or is unable or unwilling to accept the Prize for any reason, that person forfeits the right to win the Prize. 

If a Selected Entrant: (a) cannot be contacted in accordance with these Contest Rules for any reason; (b) does not or cannot accept the Prize for any reason; (c) fails to meet all of the eligibility criteria set out in these Contest Rules (as determined by the Sponsor in its sole discretion); (d) fails to comply with these Contest Rules (as determined by the Sponsor in its sole discretion); and/or (e) fails to correctly answer the skill testing question, then the Selected Entrant will be disqualified and will not receive a Prize (and waives all rights to a Prize), and another entrant will be selected by way of random draw from the remaining eligible entries.

To be declared a Prize winner, a Selected Entrant must: (a) meet all of the eligibility criteria set out in these Contest Rules (as determined by the Sponsor in its sole discretion); (b) comply with these Contest Rules (as determined by the Sponsor in its sole discretion); and (c) correctly answer the skill testing question. The Prizes will only be awarded to the declared winners. The Prize will be delivered by the Sponsor in such manner as chosen to be appropriate by the Sponsor at the Sponsor’s full discretion.

Disputes regarding identity of entrant: If the identity of a Selected Entrant is disputed, the entry will be deemed to have been submitted by the individual assigned to the email address associated with the entry (the “Authorized Account Holder”). The Selected Entrant may be required to provide proof that they are the Authorized Account Holder associated with the selected entry.

The Sponsor assumes no liability after delivering the Prize, and each winner must assume the risk of loss or damage to the Prize after shipping. The Sponsor makes no representation or warranty of any kind with respect to the appearance, safety or performance of the Prize. The Prize will be delivered to the winner by mail or email, as determined by the Sponsor at its sole discretion within five (5) weeks of the Draw Date (as outlined in Schedule A). The Sponsor is not responsible for contacting or forwarding the Prize to a winner who provides unclear or incomplete information, or for prizes lost, misdirected, delayed, or destroyed while in transit.
</p>
        </div>

        <!-- Release and Indemnification -->
        <div class="termsEntry">
            <h2>RELEASE AND INDEMNIFICATION</h2>
            <p>The Sponsor and each of their respective departments and agencies, parent, related and affiliated companies, subsidiaries, franchisees, advertising and promotional agencies, counsel, marketing partners, and each of their respective directors, officers, employees, shareholders, successors, licensees, subsidiaries, agents, artists, advisors, assignees, and all others associated with the administration, development and execution of the Contest (hereinafter referred to as the “Released Parties”) are not responsible or liable for: (i) incorrect or inaccurate entry information, or anything  which may affect a person’s ability to participate in the Contest or be awarded a Prize, including but not limited to human error, technical malfunctions, lost, delayed, damaged, misdirected, illegible or incomplete entries, or any combination thereof, and entries which fail to fully comply with these Contest Rules; (ii) technical failures of any kind, including, but not limited to malfunctions, interruptions, or disconnections in network lines, computer systems, servers, Internet service providers, hardware or software; (iii) injury or damage to the entrant’s computer or to any other computer related to or resulting from participating in the Contest or accepting the Prize; (iv) any injury or damage to persons or property which may be caused, directly or indirectly, in whole or in part, from entrant’s participation in the Contest or receipt or use or misuse of the Prize; (v) the security or privacy of information transmitted via computer networks or for breaches of privacy due to interference by third party computer “hackers” or otherwise; (vi) late, lost, misdirected or unsuccessful efforts to notify a potential winner.
By entering or attempting to enter the Contest, each entrant agrees to forever release, discharge, indemnify, and hold harmless the Released Parties from and against any and all liability for any losses, damages, costs, and expenses the entrant, their heirs, successors or assigns have, might have or could have suffered, in connection with the entrant’s participation or attempted participation in the Contest and/or the entrant’s acceptance, receipt, possession, use or misuse of the Prize, including without limitation personal injury, death, or property damage.
</p>
        </div>

        <!-- Use of Personal Information -->
        <div class="termsEntry">
            <h2>USE OF PERSONAL INFORMATION</h2>
            <p>By entering the Contest, the Selected Entrant authorizes the Sponsor and their representatives to use their name, photograph, image, voice, place of residence and/or statement relating to the Prize for publicity purposes, without any form of compensation. No communication, commercial, or otherwise, not related to the Contest will be sent to the entrant, unless he or she has otherwise consented. Please refer to the online privacy policy at https://www.canopygrowth.com/privacy-policy/.</p>
        </div>

        <!-- Prize Conditions -->
        <div class="termsEntry">
            <h2>PRIZE CONDITIONS</h2>
            <p>The Prize must be accepted as awarded and is not transferable or assignable (except as may be specifically permitted by Sponsor in its sole and absolute discretion). No substitutions are permitted, except at Sponsor’s option. Sponsor reserves the right, in its sole and absolute discretion, to substitute any Prize or a component thereof with a prize of equal or greater retail value. The Sponsor and the other Released Parties make no representation and do not offer any warranty, express or implied, as to the quality or fitness of any Prize awarded in connection with the Contest. To the fullest extent permitted by applicable law, each confirmed winner understands and acknowledges that they may not seek reimbursement or pursue any legal or equitable remedy from either the Sponsor or any of the other Released Parties should their Prize fail to be fit for its purpose or is in any way unsatisfactory. For greater certainty and the avoidance of any doubt, by accepting a Prize the confirmed winner agrees to waive all recourse against the Sponsor and all of the other Released Parties if the Prize or a component thereof does not prove satisfactory, either in whole or in part.
</p>
        </div>

        <!-- Other Conditions -->
        <div class="termsEntry">
            <h2>OTHER CONDITIONS</h2>
            <p>The Sponsor reserves the right to terminate, suspend, or amend this Contest at any time and in any way, without prior notice. Without limiting the foregoing, if, for any reason, the Contest is not capable of running as originally planned for any reason, the Sponsor reserves the right to cancel the Contest and conduct a random draw from all previously received eligible entries received by the Contest End Date (as outlined in Schedule A).

Law: The Contest is void where prohibited by law and is subject to all applicable Canadian federal, provincial, territorial, municipal, and local laws. This Contest shall be governed exclusively by the laws of Ontario, including all issues and questions concerning the construction, validity, interpretation and enforceability of these Contest Rules, rights and obligations between entrants and the Sponsor, and procedural provisions, without giving effect to any choice of law or conflict of law rules. Any dispute shall be adjudicated by the courts sitting in Ottawa, Ontario, Canada.

Intellectual Property: All intellectual property, including but not limited to trademarks, logos, designs, promotional materials, web pages, source codes, images, drawings, illustrations, slogans and representations are owned or licensed by the Sponsor or used with permission from the owner. All rights are reserved. Unauthorized copying or use of any copyrighted material or other intellectual property without the express written consent of the Sponsor is strictly prohibited. The Sponsor’s marketing or other partners, if any, shall also have access to and rights to reproduce, copy or otherwise use any materials generated by this Contest or any submissions or materials generated by entrants.
</p>
<br/> <br/>
<img src="images/new/schedule.png" />
        </div>

        <button class="button_selected default_btn terms_agree_btn" style="margin-bottom: 3em;">BACK TO EXPERIENCE</button>
    </div>
    <div class="pageFrameCheckeredFooter"></div>
</section>


    </main>

    <!-- Lottie library from CDN -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lottie-web/5.10.1/lottie.min.js"></script>

    <!-- Linking the JavaScript file -->
    <script src="js/index.js"></script>
    <script src="js/stores.js"></script>
    <script src="js/store_selection.js"></script>
    <script src="js/transitions.js"></script>
    <script src="js/pageURLS.js"></script>
    <script src="js/wheel.js"></script>

    <!-- Client -->
    <script src="client/age_gate_interaction.js"></script>
    <script src="client/validate_and_submit.js"></script>
    <script src="client/dataIntake_submission_form.js"></script>
    <script src="client/track_user.js"></script>
   
</body>
</html>
