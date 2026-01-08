@include('common.wo_login.header')
@include('common.wo_login.start_scripts')
@include('common.wo_login.end_scripts')

<style type="text/css">body, p, h1, h2, h3, h4, h5, h6 {font-family: times new roman !important;color: #000; }</style>
<section class="<?=(isset($data['background-class']) && !empty($data['background-class']))?$data['background-class']:"heading"?>" id="box">
    <div class="text-box">
        <h1 style="color: #D94E29">{!! $data['banner_heading'] !!}</h1>
        <p class="text-dark">Our peer-to-peer rental platform helps catalog items you own, and rent them privately with friends and neighbors. Here's how it works</p>
        @if(isset($data['is_banner_link']) && $data['is_banner_link'])
        <div class="bannersecbtn">
            <a href="{{$data['banner_link']}}">{{$data['banner_text']}}</a>
        </div>
        @else
        <p>{{ $data['banner_text']}}</p>
        @endif
    </div>
</section>

<section class="long-legal-version">
    <div class="container">
        <br>
        <h2><b>DixWix’s Terms of Service</b></h2>
        <h3>Key Points:</h3>
        <div class="content-inner">
            <h4> 1. Service Overview </h4>
            <ul class="term-condition-list">
                <li>DixWix is a platform where users can lend items ("Owners") to other users ("Renters") for a fee or compensation.</li>
                <li>DixWix acts as an intermediary and is not directly party to any lending transactions.</li>
                <li>Users must be at least 14+ years old to use the service.</li>
            </ul>
            <h4>2. Fees and Payments</h4>
            <ul class="term-condition-list">
                <li>DixWix deducts 5% from owners transactions towards platform processing cost.</li>
                <li>Renters can pay an additional 5% commission depending on the item's category.</li>
                <li>Payments are processed through Intuit and Rewards through GiftOgram.</li>
                <li>Cash redemption of rewards would incur local taxes and processing fees.</li>
                <li>Arranging & making transactions outside the platform would be a violation of Terms Of Services (TOS).</li>
            </ul>

            <h4>3. User Responsibilities as Owners:</h4>
            <ul class="term-condition-list">
                <li>Must provide accurate item descriptions and images.</li>
                <li>Maintaining items in good and safe working condition.</li>
                <li>Shall timely accept or reject rental requests.</li>
                <li>Accept adverse user ratings for poor or unsafe items.</li>
                <li>Accept normal wear and tear from rentals.</li>
            </ul>

            <h4>4. User Responsibilities as Renters:</h4>
            <ul class="term-condition-list">
                <li>Must maintain and return items on time.</li>
                <li>Take responsibility for damage, loss, or theft of items.</li>
                <li>Replace damaged items or provide fair mutually agreed compensation to the owner.</li>
                <li>Pay for late returns if requested by the owner.</li>
                <li>Accept adverse ratings for damages to rented items.</li>
            </ul>

            <h4>5. Dispute Resolution</h4>
            <ul class="term-condition-list">
                <li>Users agree to mandatory individual arbitration for disputes.</li>
                <li>Class action lawsuits are waived by all users of the platform.</li>
                <li>Disputes between users would be mediated first by group admins and members.</li>
                <li>Disputes raised to DixWix for mediation would incur a $25 arbitration fee for both parties.</li>
            </ul>

            <h4>6. Limitations and Liability</h4>
            <ul class="term-condition-list">
                <li>Service provided "as is" without warranties.</li>
                <li>DixWix is not responsible for:
                    <ul class="term-condition-list">
                        <li>Disputes between users.</li>
                        <li>Lost, stolen, or damaged items.</li>
                        <li>Third-party content or links.</li>
                        <li>Service interruptions or technical issues.</li>
                        <li>Any form of damage or loss to property or life.</li>
                    </ul>
                </li>
                <li>Users release DixWix from liability related to lending transactions.</li>
            </ul>

            <h4>7. Prohibited Items</h3>
            <ul class="term-condition-list">
                <li>Alcohol, tobacco, drugs.</li>
                <li>Illegal items.</li>
                <li>Pornography.</li>
                <li>Items violating intellectual property rights.</li>
                <li>Animals and animal products.</li>
                <li>Items in poor or unsafe condition.</li>
            </ul>

            <h4>8. Content Rights</h3>
            <ul class="term-condition-list">
                <li>Users retain ownership of their content and items.</li>
                <li>DixWix gets a license to use user content for service purposes.</li>
                <li>DixWix can remove users, content, groups, and items without notice.</li>
            </ul>

            <h4>9. Account Terms</h3>
            <ul class="term-condition-list">
                <li>Users must provide accurate information.</li>
                <li>Responsible for maintaining account security.</li>
                <li>One account per user.</li>
                <li>DixWix Platform can terminate accounts at its discretion or for TOS violations.</li>
            </ul>
            <br>

            <h3>Long Legal Version: Last Updated Jan 20th, 2025</h3>
            <br>
            <h4>Disclaimer Of Liability And Assumption Of Risk</h4>
            <br>
            <p>By using DixWix.com (the "Platform"), you expressly acknowledge and agree that:</p>
            <p><b>1. General Disclaimer:</b> The Platform serves solely as an intermediary service connecting renters with equipment owners. The Platform does not own, control, maintain, or inspect any items listed for rent, nor does it verify the safety, legality, or suitability of any items.</p>
            <p>
                <b>2. Assumption of Risk:</b> You understand and explicitly accept that using items rented through the Platform involves inherent risks. You voluntarily assume full responsibility for any and all risks of injury, illness, damage, loss, or death that may occur in connection with items rented through the Platform.
            </p>
            <p><b>3. Waiver of Liability:</b> To the maximum extent permitted by applicable law, you hereby release, waive, discharge, and covenant not to sue the Platform, its officers, directors, employees, agents, subsidiaries, affiliates, licensors, and successors from any and all liability, claims, demands, actions, and causes of action whatsoever arising out of or related to:
                <br>
                <ul class="term-condition-list">
                    <li>a) Any physical injury, disability, or death;</li>
                    <li>b) Mental or emotional distress or trauma;</li>
                    <li>c) Property damage, loss, or destruction;</li>
                    <li>d) Financial losses or damages;</li>
                    <li>e) Any other direct, indirect, incidental, special, exemplary, or consequential damages, whether caused by negligence or otherwise, arising from or in any way connected with:
                        <ul class="term-condition-list">
                            <li> The use, misuse, or malfunction of any rented items;</li>
                            <li> The condition, maintenance, or operation of any rented items;</li>
                            <li> Any defects, known or unknown, in rented items;</li>
                            <li> Any instructions or lack thereof regarding rented items;</li>
                            <li> Any incidents involving rented items.</li>
                        </ul>
                    </li>
                </ul>
            </p>
            <p>
                <b>4. Third-Party Conduct:</b> The Platform is not responsible for the actions, errors, omissions, representations, warranties, breaches, or negligence of any renters, owners, or third parties, or for any personal injuries, death, property damage, or other damages or expenses resulting therefrom.
            </p>
            <p>
                <b>5. Indemnification:</b> You agree to indemnify and hold harmless the Platform from any claims, losses, liabilities, demands, damages, costs, and expenses, including reasonable attorneys' fees, arising out of or in any way connected with your use of items rented through the Platform.
            </p>
            <p>
                <b>6. Severability:</b> If any portion of this disclaimer is found to be void or unenforceable, the remaining portions shall remain in full force and effect.
            </p>
            <p>
                <b>7. Acknowledgment:</b> By using the Platform, you acknowledge that you have read and understood this disclaimer, and voluntarily accept its terms, including the risk of injury, death, property damage, and other losses.
            </p>
            <p>This disclaimer shall be governed by and construed in accordance with applicable state and federal laws. Your use of the Platform constitutes your agreement to be bound by this disclaimer.</p>
            <h4>The Platform Strongly Recommends That All Users Maintain Appropriate Insurance Coverage And Exercise Caution When Using Rented Items.</h4>
            <br>
            <h2><b>Terms of Service</b></h2>
            <br>
            <p>Welcome to <a href="https://www.dixwix.com" target="_blank">https://www.dixwix.com</a> (the "DixWix Technologies LLC"), a website owned and operated by DixWix Technologies LLC, Inc. ("DixWix Technologies LLC", "we", "our", or "us"). This page explains the terms by which you may use the DixWix Technologies LLC, our online and/or mobile services, and our related software provided on or in connection with the service (collectively, the "DixWix.com Service").</p>
            <p><b>Please Read This Agreement Carefully To Ensure That You Understand Each Provision</b> Here’s your text converted to normal sentence case (only the first letter of sentences and proper nouns capitalized): By accessing or using the DixWix.com service, by registering for an account on the DixWix.com service, or by clicking a button or checking a box marked "I agree" or something similar, you signify that you have read, understood, and agree to be bound by these terms of use (these "Terms") and to the collection and use of your information as set forth in the DixWix Technologies LLC Privacy Policy <a href="https://www.dixwix.com/privacy-policy" target="_blank">https://www.dixwix.com/privacy-policy</a>, which is hereby incorporated by reference. These terms apply to all visitors, users, and others who register for or otherwise access the DixWix.com service ("Users").</p>
            <p>You acknowledge and agree that, as provided in greater detail in these terms:</p>
            <ul class="term-condition-list">
                <li>Any and all lending transactions (as defined below) on the DixWix.com service are solely between the owner and renter and you expressly acknowledge and agree that DixWix Technologies LLC is not a party to any lending transactions and is not obligated to monitor any lending transaction or resolve any disputes between its users;</li>
                <li>If you are an owner (as defined below), you authorize DixWix Technologies LLC and its third-party payment processors to charge your payment method for any cancellation fees in accordance with the terms and conditions of these terms;</li>
                <li>If you are a renter (as defined below), you authorize DixWix Technologies LLC and its third-party payment processors to charge your payment method for any lost, stolen, or damaged items;</li>
                <li>DixWix Technologies LLC reserves the right in its sole discretion to change these terms and the DixWix.com service and to determine the method and manner of notice of the changes;</li>
                <li>These terms contain a mandatory individual arbitration and class action/jury trial waiver provision that requires the use of arbitration on an individual basis to resolve disputes, rather than jury trials or class actions.</li>
            </ul>
            <p>Certain services may be subject to additional terms and conditions specified by us from time to time, and your use of such services is subject to those additional terms and conditions, which are hereby incorporated into these Terms by reference.</p>
                <h4>The DixWix.com Service</h4>
            <p>The DixWix.com Service is an online platform that enables Users to loan out their items (such Users, the "Lenders") to other Users who desire to borrow the Owners' items (such Users, the "Renters") in exchange for payment of fees to the applicable Owner and DixWix Technologies LLC. Each such lending transaction between Owners and Renters shall, for the purposes of these Terms, be referred to as a "Lending Transaction" and each item that is the subject of a Lending Transaction shall for the purposes of these Terms, be referred to as an "Item".</p>
            <br>
            <h3><b>Use of the DixWix.com Service</b></h3>
            <br>
            <h4>A. Eligibility</h4>
            <p>This is a contract between you and DixWix Technologies LLC. You must read and agree to these Terms before using the DixWix.com Service. If you do not agree, you may not use the DixWix.com Service. You may use the DixWix.com Service only if you can form a binding contract with DixWix Technologies LLC, and only in compliance with these Terms and all applicable local, state, national, and international laws, rules, and regulations. Any use or access to the DixWix.com Service by anyone under sixteen (16) years of age is strictly prohibited and in violation of these Terms. The DixWix.com Service is not available to any Users previously removed from the DixWix.com Service by DixWix Technologies LLC. DixWix Technologies LLC reserves the right to approve or reject any Users from joining or continuing to use the DixWix.com Service, except as prohibited by applicable law.</p>
            <h4>B. Limited License</h4>
            <p>Subject to the terms and conditions of these Terms, you are hereby granted a non-exclusive, limited, non-transferable, freely revocable license to use the DixWix.com Service solely as permitted by the features of the DixWix.com Service. DixWix Technologies LLC reserves all rights not expressly granted herein in the DixWix.com Service and the DixWix Technologies LLC Content (as defined below). DixWix Technologies LLC may terminate this license at any time for any reason or no reason.</p>
            <h4>C. DixWix Technologies LLC Accounts</h4>
            <p>Your DixWix Technologies LLC account gives you access to the services and functionality that we may establish and maintain from time to time and in our sole discretion. When creating your account, you must provide accurate and complete information, and you must keep this information up to date. You are solely responsible for the activity that occurs on your account, and you must keep your account password secure. We encourage you to use "strong" passwords (passwords that use a combination of upper and lower-case letters, numbers, and symbols) with your account. We may maintain different types of accounts for different types of Users. If you open a DixWix Technologies LLC account on behalf of a company, organization, or other entity, then: (i) "you" includes you and that entity; and (ii) you represent and warrant that you are an authorized representative of the entity with the authority to bind the entity to these Terms, and that you agree to these Terms on the entity's behalf. By connecting to the DixWix.com Service with a third-party service, you give us permission to access and use your information from that service as permitted by that service, and to store your log-in credentials for that service. You may never use another User's account. You must notify DixWix Technologies LLC immediately of any breach of security or unauthorized use of your account. DixWix Technologies LLC will not be liable for any losses caused by any unauthorized use of your account. You may control your User profile and how you interact with the DixWix.com Service by changing the settings in your profile page. By providing DixWix Technologies LLC your email address, you consent to our using the email address to send you DixWix.com Service-related notices, including without limitation any notices required by law, in lieu of communication by postal mail. We may also use your email address to send you other messages, such as changes to features of the DixWix.com Service and special offers. If you do not want to receive such email messages, you may opt out or change your preferences in your profile page. Opting out may prevent you from receiving email messages regarding updates, improvements, or offers.</p>
            <h4>D. DixWix.com Service Rules</h4>
            <p>You agree not to engage in any of the following prohibited activities:</p>
            <ul class="term-condition-list">
                <li>Downloading, copying, distributing, or disclosing any part of the DixWix.com Service in any medium, including without limitation by any automated or non-automated "scraping";</li>
                <li>Using any automated system, including without limitation "robots," "spiders," "offline readers," etc., to access the DixWix.com Service in a manner that sends more request messages to the DixWix Technologies LLC servers than a human can reasonably produce in the same period of time by using a conventional online web browser (except that DixWix Technologies LLC grants the operators of public search engines revocable permission to use spiders to copy publicly available materials from the DixWix Technologies LLC for the sole purpose of and solely to the extent necessary for creating publicly available searchable indices of the materials, but not caches or archives of such materials);</li>
                <li>Transmitting spam, chain letters, or other unsolicited email or messages (including, but not limited to, unsolicited requests for donations);</li>
                <li>Attempting to interfere with, compromise the system integrity or security, or decipher any transmissions to or from the servers running the DixWix.com Service;</li>
                <li>Taking any action that imposes, or may impose at our sole discretion an unreasonable or disproportionately large load on our infrastructure;</li>
                <li>Uploading invalid data, viruses, worms, or other software agents through the DixWix.com Service;</li>
                <li>Collecting or harvesting any personally identifiable information, including, but not limited to, account names, from the DixWix.com Service;</li>
                <li>Using the DixWix.com Service for any commercial solicitation purposes;</li>
                <li>Impersonating another person or otherwise misrepresenting your affiliation with a person or entity, conducting fraud, hiding, or attempting to hide your identity;</li>
                <li>Interfering with the proper working of the DixWix.com Service;</li>
                <li>Accessing any content on the DixWix.com Service through any technology or means other than those provided or authorized by the DixWix.com Service;</li>
                <li>Modifying, disassembling, decompiling, or reverse-engineering the DixWix.com Service, except to the extent that such restriction is expressly prohibited by law;</li>
                <li>Selling any counterfeit or illegal items;</li>
                <li>Using the DixWix.com Service in violation of applicable law;</li>
                <li>Using the DixWix.com Service to harass or abuse another User;</li>
                <li>Bypassing the measures we may use to prevent or restrict access to the DixWix.com Service, including without limitation features that prevent or restrict use or copying of any content or enforce limitations on the use of the DixWix.com Service or the content therein.</li>
            </ul>
            <h4>E. Changes to the DixWix.com Service</h4>
            <p>We may, without prior notice, change the DixWix.com Service; stop providing the DixWix.com Service or features of the DixWix.com Service, to you or to Users generally; or create usage limits for the DixWix.com Service. We may permanently or temporarily terminate or suspend your access to the DixWix.com Service without notice and liability for any reason, including without limitation if in our sole determination you violate any provision of these Terms, or for no reason. Upon termination for any reason or no reason, you continue to be bound by these Terms.</p>
            <h4>F. Disputes with Other Users</h4>
            <p>You are solely responsible for your interactions with other Users – including, but not limited to, any Lending Transactions. We reserve the right, but have no obligation, to monitor disputes between you and other Users. DixWix Technologies LLC shall have no liability for your interactions with other Users, or for any User's action or inaction.</p>
            <br>
            <h2><b>User Content</b></h2>
            <br>
            <p>Any and all photographs, articles, images, graphics, videos, sounds, music, audio recordings, text, files, profiles, communications, comments, feedback, suggestions, ideas, concepts, questions, data or other content that you: (i) submit or post on or through the DixWix.com Service, on any of our blogs, social media accounts or through tools or applications we provide for posting or sharing such content with us; or (ii) have posted or uploaded to your social media accounts which are tagged with any DixWix Technologies LLC promoted hashtag (collectively "User Content"), shall be deemed nonconfidential and nonproprietary. You understand that certain portions of the DixWix.com Service may allow other Users to view, edit, share, and/or otherwise interact with your User Content. By providing or sharing User Content through the DixWix.com Service, you agree to allow others to view, edit, share, and/or interact with your User Content in accordance with your settings and these Terms.</p>
            <p><b>We claim no ownership rights over the user content created by you. The user content remains yours.</b> However, by submitting or posting any User Content, you hereby expressly grant to DixWix Technologies LLC and its affiliates a perpetual, irrevocable, royalty-free, worldwide, sublicensable and transferable license to copy, publish, translate, modify, reformat, create derivative works from, distribute, reproduce, sell, display, transmit, publish, broadcast, host, archive, store, cache, use or otherwise exploit all or any portion of the User Content, as well as your name, persona and likeness included in any User Content and your social media account handle, username, real name, profile picture and/or any other information associated with the User Content, in any commercial or noncommercial manner whatsoever, in whole or in part, in any and all distribution channels, forms, media or technology, whether now known or hereafter developed, for use in connection with the DixWix.com Service and DixWix Technologies LLC's (and its successors' and affiliates') business, including without limitation for promoting and redistributing part or all of the DixWix.com Service (and derivative works thereof) in any media formats and through any media channels, including, but not limited to, in stores, printed marketing materials, emails, web pages, social media accounts, without attribution and without further notice to you. Neither you, nor any other person or entity, will have the right to (i) receive any royalty or consideration of any kind for the use of the User Content pursuant to these Terms or (ii) inspect or approve the editorial copy or other material that may be used in connection with the User Content. You also hereby grant each User of the DixWix.com Service a non-exclusive license to access your User Content through the DixWix.com Service, and to use, reproduce, distribute, display and perform such User Content as permitted through the functionality of the DixWix.com Service and under these Terms.</p>

            <p>By submitting or posting User Content on the DixWix.com Service, on your social media accounts or through any tools or applications we provide for posting or sharing your User Content with us, you represent and warrant that:</p>
            <ul class="term-condition-list">
                <li>you own or control any and all rights in and to the User Content and/or have the rights to grant all of the rights and licenses in these Terms, and if you are not the holder of such rights, the holder of such rights has completely and effectively waived all such rights and irrevocably granted you the right to grant the licenses stated above without the need for payment to you or any other person or entity;</li>
                <li>you have obtained permission from any individuals that appear in the User Content to use, and grant others the right to use, their name, image, voice and/or likeness without the need for payment to you or any other person or entity;</li>
                <li>your User Content and DixWix Technologies LLC's use thereof as contemplated by these Terms and the Service will not violate any law or infringe any rights of any third party, including, but not limited to, any Intellectual Property Rights and privacy rights;</li>
                <li>the User Content does not (a) contain false or misleading information, (b) infringe on the intellectual property, privacy, publicity, statutory, contractual or other rights of any third party, (c) contain any libelous, defamatory, obscene, offensive, racist, threatening or otherwise harassing or hateful content, (d) contain any addresses, email addresses, phone numbers or any contact information or (e) contain computer viruses, worms or other harmful files;</li>
                <li>DixWix Technologies LLC may exercise the rights to your User Content granted under these Terms without liability for payment of any guild fees, residuals, payments, fees, or royalties payable under any collective bargaining agreement or otherwise; and</li>
                <li>to the best of your knowledge, all your User Content and other information that you provide to us is truthful and accurate.</li>
            </ul>

            <p>You are solely responsible for the User Content and you hereby agree to indemnify and hold DixWix Technologies LLC and its employees, agents, affiliates, assigns and licensees harmless from any and all damages, claims, expenses, costs or fees arising from or in connection with a breach of any of the foregoing representations or your violation of any law or rights of a third party.</p>

            <p>DixWix Technologies LLC does not guarantee the truthfulness, accuracy or reliability of any User Content or endorse any opinions expressed by you or anyone else. By submitting or posting the User Content you fully and unconditionally release and forever discharge DixWix Technologies LLC and its officers, directors, employees and agents from any and all claims, demands and damages (actual or consequential, direct or indirect), whether now known or unknown, of every kind and nature relating to, arising out of or in any way connected with: (i) disputes between you and one or more users or any other person or entity, or (ii) the use by DixWix Technologies LLC or you of the User Content, including without limitation any and all claims that use of the User Content pursuant to these Terms violates any of your intellectual property rights, copyrights, rights of publicity or privacy, "moral rights," or rights of attribution and integrity. You acknowledge and agree that DixWix Technologies LLC has no control over and shall have no liability for any damages resulting from, the use (including without limitation re-publication) or misuse by you or any third party of any User Content. DixWix Technologies LLC acts as a passive conduit for User Content and has no obligation to screen or monitor User Content. If DixWix Technologies LLC becomes aware of any User Content that allegedly may not conform to these Terms, DixWix Technologies LLC may investigate the allegation and determine in its sole discretion whether to take action in accordance with these Terms. Upon request by DixWix Technologies LLC, you will furnish DixWix Technologies LLC any documentation, substantiation or releases necessary to verify your compliance with these Terms. DixWix Technologies LLC has no liability or responsibility to Users for performance or nonperformance of such activities.</p>
            <h2><b>DixWix Technologies LLC Rights and Proprietary Rights</b></h2>
            <p><b>DixWix Technologies LLC has the absolute right to remove and/or delete without notice any user content within its control that it deems objectionable.</b> YOU CONSENT TO SUCH REMOVAL AND/OR DELETION AND WAIVE ANY CLAIM AGAINST DixWix Technologies LLC FOR SUCH REMOVAL AND/OR DELETION. DixWix Technologies LLC IS NOT RESPONSIBLE OR LIABLE FOR FAILURE TO STORE POSTED CONTENT OR OTHER MATERIALS YOU TRANSMIT THROUGH THE DixWix.com Service. YOU SHOULD TAKE MEASURES TO PRESERVE COPIES OF ANY DATA, MATERIAL, CONTENT OR INFORMATION YOU POST ON THE DixWix.com Service OR ANY OTHER SITES OR PLATFORMS.</p>

            <h4>Our Proprietary Rights</h4>
            <p>For the purposes of these Terms, "Intellectual Property Rights" means all patent rights, copyright rights, mask work rights, moral rights, rights of publicity, trademark, trade dress and service mark rights, goodwill, trade secret rights and other intellectual property rights as may now exist or hereafter come into existence, and all applications therefore and registrations, renewals and extensions thereof, under the laws of any state, country, territory or other jurisdiction.</p>

            <p>Except for your User Content, the DixWix.com Service and all materials therein or transferred thereby, including without limitation software, images, text, graphics, illustrations, logos, patents, trademarks, service marks, copyrights, photographs, audio, videos, music, and User Content belonging to other Users (the "DixWix Technologies LLC Content"), and all Intellectual Property Rights related thereto, are the exclusive property of DixWix Technologies LLC and its licensors (including without limitation other Users who post User Content to the DixWix.com Service). Except as explicitly provided herein, nothing in these Terms shall be deemed to create a license in or under any such Intellectual Property Rights, and you agree not to sell, license, rent, modify, distribute, copy, reproduce, transmit, publicly display, publicly perform, publish, adapt, edit or create derivative works from any DixWix Technologies LLC Content. Use of the DixWix Technologies LLC Content for any purpose not expressly permitted by these Terms is strictly prohibited.</p>

            <p>You may choose to or we may invite you to submit comments or ideas about the DixWix.com Service, including without limitation about how to improve the DixWix.com Service or our products ("Ideas"). By submitting any Idea, you agree that your disclosure is gratuitous, unsolicited and without restriction and will not place DixWix Technologies LLC under any fiduciary or other obligation, and that we are free to use the Idea without any additional compensation to you, and/or to disclose the Idea on a non-confidential basis or otherwise to anyone. You further acknowledge that, by acceptance of your submission, DixWix Technologies LLC does not waive any rights to use similar or related ideas previously known to DixWix Technologies LLC, or developed by its employees, or obtained from sources other than you.</p>
            <h3>Terms Specific for Owners</h3><br>
            <h4>A. Lending Transaction Acceptance and Cancellation</h4><br>
            <p>You may accept or reject any request from a Renter to enter into a Lending Transaction at your sole discretion. Once you accept a request to enter into a Lending Transaction, a legally binding agreement is formed between you and the applicable Renter. Once you enter into a Lending Transaction with a Renter, you agree to be responsive to the Renter and to communicate with them to coordinate the delivery and return of the Item. If you, as an Owner, cancel a Lending Transaction after you accept the Renter's request, you shall be liable to pay, and you authorize DixWix Technologies LLC and its third-party payment processor to charge your payment method for, a cancellation charge of thirty percent (30%) of the Hire Fee (as defined below) and DixWix Technologies LLC may, at its sole discretion, credit the applicable Renter's account for a portion of your cancellation charge.</p>

            <h4>B. Item Listings and Descriptions</h4>
            <p>When listing an Item for Renters to borrow through the DixWix.com Service (such items, the "Items"), Owners must: (i) provide complete and accurate information and descriptions about the Items; (ii) disclose any deficiencies, restrictions, and requirements that apply; and (iii) provide any other pertinent information requested by DixWix Technologies LLC. Images or videos used in the Owners' Item listings must accurately reflect the quality and condition of your Items. DixWix Technologies LLC reserves the right to require that Item listings and descriptions have a minimum number of images or videos of a certain format, size, and resolution.</p>

            <h4>C. Hire Fee and Acceptance; Payment</h4>
            <p>You are solely responsible for setting a price (including without limitation any taxes if applicable, or charges such as delivery fees) for the Renter to rent your Items ("Hire Fee"). Once a Renter requests to borrow your Items, you may not request that the Renter pays a higher price than in the request, nor may you do any subsequent verification of the Renter. Unless otherwise agreed by the parties in writing, DixWix Technologies LLC shall remit payment to you of the Hire Fee due to you, less the DixWix Technologies LLC Commission (as defined below), no later than twenty-four (24) hours after scheduled start of Renter's Item rental. Payment shall be in the form you select when you register for the DixWix.com Service, or as subsequently updated as permitted by the DixWix.com Service. DixWix Technologies LLC reserves the right to withhold payment or charge back to your account any amounts otherwise due to us under these Terms or amounts due to any breach of these Terms by you, pending DixWix Technologies LLC's reasonable investigation of such breach. DixWix Technologies LLC also reserves the right to withhold payment or charge back to your account any amounts subject to dispute, such as in the case of credit card charge backs, pending successful resolution of the dispute. To ensure proper payment, you are solely responsible for providing and maintaining accurate contact and payment information associated with your account, which includes without limitation applicable tax information. If we believe that we are obligated to obtain tax information and you do not provide this information to us after we have requested it, we may withhold your payments until you provide this information or otherwise satisfy us that you are not a person or entity from whom we are required to obtain tax information. Any third-party fees related to returned or cancelled payments due to a contact or payment information error or omission may be deducted from the newly issued payment. You agree to pay all applicable taxes or charges imposed by any government entity in connection with your participation in the DixWix.com Service. If you dispute any payment made hereunder, you must notify DixWix Technologies LLC in writing within thirty (30) days of such payment or from when you purport such payment would have been due, whichever is earlier. Failure to so notify DixWix Technologies LLC shall result in the waiver by you of any claim relating to such disputed payment. We may withhold any taxes or other amounts from payments due to you as required by law.</p>

            <h4>D. Prohibited Items</h4>
            <p>You will not list or loan the following Items on the DixWix.com Service: (i) alcohol, tobacco, drugs and drug paraphernalia; (ii) illegal items, items promoting illegal activity and highly regulated items; (iii) pornography or mature content; (iv) items that violate the Intellectual Property Rights or other proprietary rights of any third party; (v) animals and animal products; and/or (vi) any Items that are not clean or in good condition. DixWix Technologies LLC reserves the right to amend this list of prohibited Items at any time and for any or no reason and to otherwise remove any Items listed on the DixWix.com Service, whether or not they are included on this list of prohibited Items.</p>

            <h4>E. Item Representations and Warranties</h4>
            <p>When you enter into a Lending Transaction as an Owner, you represent and warrant that: (i) you are in possession of all licenses and permits necessary to provide the Items to the Renters and DixWix Technologies LLC pursuant to these Terms; and (ii) the Items, your provision of the Items, and DixWix Technologies LLC's and Renters' use of the Items under these Terms will (a) not breach any agreements you have entered into with any third parties, (b) comply with all applicable laws, tax requirements, and other rules and regulations, and (c) will not violate any third party's proprietary rights, including, but not limited to, any Intellectual Property Rights and privacy rights.</p>

            <h4>F. Special Terms for Storage</h4>
            <p>If you offer to lend out your storage space (including, but not limited to, garages, lofts, attics, rooms, storage units, studios, and driveways), you acknowledge and agree that you: (i) are entirely responsible for providing proper security for the items being stored in your Items; (ii) are responsible for maintaining the condition of the storage space, to at least the condition that it is in when the Renter agrees to leave their items in the storage space; and (iii) are responsible for the care and protection of any and all Renter items contained in your storage space.</p>

            <h4>G. Item Rankings</h4>
            <p>The placement and ranking of Items in search results on the DixWix.com Service may vary and depend on a variety of factors, such as Renter search parameters and preferences, Owner requirements, price and calendar availability, number and quality of images, customer service and cancellation history, and Reviews and Ratings.</p>

            <h4>H. Owner Guarantee</h4>
            <p>As part of the DixWix.com Service, DixWix Technologies LLC may allow Owners to be compensated for any Items that are lost, stolen or damaged by filing a claim with DixWix Technologies LLC. Details about the guarantee, including, but not limited to, any eligibility requirements, can be found at dixwix.com/guarantee. DixWix Technologies LLC reserves the right to discontinue this Owner guarantee for any or no reason and without notice to you or to Owners generally.</p>

            <h4>I. Lending Transaction Acknowledgement; Assumption of Risk; Release of Claims</h4>
            <p>When you lend an Item to a Renter as part of a Lending Transaction, you acknowledge and agree that you are entering into a transaction with the applicable Renter – not DixWix Technologies LLC. YOU ACKNOWLEDGE THAT THERE ARE RISKS INHERENT TO LENDING YOUR ITEMS TO BORROWERS IN CONNECTION WITH A LENDING TRANSACTION – INCLUDING, BUT NOT LIMITED TO, LOSS OR DESTRUCTION OF YOUR ITEMS AND THAT YOU ASSUME ALL RISK IN CONNECTION WITH LENDING YOUR ITEMS THROUGH THE DixWix.com Service. WITHOUT LIMITING ANY OTHER PROVISION IN THESE TERMS AND TO THE MAXIMUM EXTENT PERMITTED BY APPLICABLE LAW, YOU EXPRESSLY WAIVE AND RELEASE DixWix Technologies LLC FROM ANY AND ALL LIABILITY, CLAIMS, CAUSES OF ACTION, OR DAMAGES ARISING FROM YOUR USE OF THE DixWix.com Service AS A LENDER, INCLUDING WITHOUT LIMITATION ANY LIABILITY ARISING OUT OF OR RELATED TO ANY LENDING TRANSACTIONS THAT YOU MAY ENTER INTO ON THE DixWix.com Service. IF YOU ARE A CALIFORNIA RESIDENT, THEN THE WAIVER OF CALIFORNIA CIVIL CODE §1542 CONTAINED IN THE "LIMITATION OF LIABILITY" SECTION OF THESE TERMS SHALL APPLY TO THIS RELEASE.</p>
            <br>
            <h2><b>Terms Specific for Renters</b></h2>
            <br>
            <h4>A. Requests to Borrow</h4>
            <p>Subject to meeting any and all requirements set by DixWix Technologies LLC and/or the Owner, you may borrow an Item through the DixWix.com Service by submitting a request to the Owner through the DixWix.com Service. DixWix Technologies LLC reserves the right, but is under no obligation to, verify your request prior to delivering it to the applicable Owner. You may withdraw your request to borrow an Item without any charge or liability by notifying DixWix Technologies LLC, provided such withdrawal is effected prior to Owner's acceptance of the request.</p>

            <h4>B. Renter Fees</h4>
            <p>All applicable fees, including without limitation any Hire Fees and the DixWix Technologies LLC Commission (as defined below) (collectively, the "Renter Fees") will be presented to you prior to submitting your request to borrow an Item. Upon receipt of a booking confirmation from DixWix Technologies LLC, a legally binding agreement is formed between you and the applicable Owner and you agree to pay the Renter Fees attributable to the applicable Lending Transaction, which shall be non-refundable – even if you cancel the Lending Transaction.</p>

            <h4>C. Limited License and Return</h4>
            <p>You understand that when you enter into a Lending Transaction, you are being granted a limited license granted by the Owner to borrow and use the Item for the period identified in your borrowing request. You agree to return the Items no later than the time that is indicated in the accepted borrowing request; provided that, you may request to extend the rental period from the Owner, who may choose to extend the rental period at the Owner's sole discretion. If you retain the Item beyond the agreed upon time or fail to use reasonable efforts to communicate with the Owner during your rental period to coordinate delivery and return of the Item, you no longer have a license to borrow and use the Item and the Owner is entitled to make you return the Items in a manner consistent with applicable law. In addition, you agree to pay for each twenty-four (24) hour period (or any portion thereof) that you retain the Item, an additional fee of up to two (2) times the average daily Hire Fee originally paid by you to cover the inconvenience suffered by the Owner and DixWix Technologies LLC, plus all applicable taxes, and any legal expenses incurred by the Owner and DixWix Technologies LLC to make you return the Item unless and until such late fees reach the estimated value of the retained Items. You authorize DixWix Technologies LLC and its third-party payment processors to charge your payment method for the fees described in this Section.</p>

            <h4>D. Damages to Items</h4>
            <p>Renters are responsible for returning the Items to Owners in the condition it was in when they received the Items. Renters are responsible for their own acts and omissions and are also responsible for the acts and omissions of any individuals whom you invite to, or otherwise provide access to or use of the Items, excluding the Owners. In the event the Item is damaged, lost, stolen or destroyed, you agree the DixWix Technologies LLC and its third-party payment processors may charge your payment method for up to the fair market value of the applicable Item to compensate for such damage, loss, or destruction of the Item.</p>

            <h4>E. Renter Representations and Warranties</h4>
            <p>By submitting a request to enter into a Lending Transaction with an Owner, you represent and warrant that: (i) you have read and accepted the description of the Item provided by the Owner; (ii) you have the funds available to cover the required payments to rent the Renter Fees and any late charges for any Items retained after the rental period; (iii) you accept responsibility for the Items and agree to pay any late fees and charges in accordance with terms of this Section; and (iv) you agree to use the Items in compliance with any and all applicable laws, rules, and regulations.</p>

            <h4>F. Special Terms for Storage</h4>
            <p>If you borrow storage space from an Owner, you acknowledge and agree that: (i) you will not store any hazardous materials in the storage space, including, but not limited to, any exotic animals or explosives; (ii) you will not store items or goods that have a fair market value in excess of $30,000; and (iii) you will not store any items in the Owner's storage space that Owners are not allowed to loan to Renters through the DixWix.com Service.</p>

            <h4>G. Lending Transaction Acknowledgement; Assumption of Risk; Release of Claims</h4>
            <p>When you borrow an Item from an Owner as part of a Lending Transaction, you acknowledge and agree that you are entering into a transaction with the applicable Owner – not DixWix Technologies LLC. WITHOUT LIMITING ANY OTHER PROVISION IN THESE TERMS AND TO THE MAXIMUM EXTENT PERMITTED BY APPLICABLE LAW, YOU EXPRESSLY WAIVE AND RELEASE DixWix Technologies LLC FROM ANY AND ALL LIABILITY, CLAIMS, CAUSES OF ACTION, OR DAMAGES ARISING FROM YOUR USE OF THE DixWix.com Service AS A BORROWER, INCLUDING WITHOUT LIMITATION ANY LIABILITY ARISING OUT OF OR RELATED TO ANY LENDING TRANSACTIONS THAT YOU MAY ENTER INTO ON THE DixWix.com Service. IF YOU ARE A CALIFORNIA RESIDENT, THEN THE WAIVER OF CALIFORNIA CIVIL CODE §1542 CONTAINED IN THE "LIMITATION OF LIABILITY" SECTION OF THESE TERMS SHALL APPLY TO THIS RELEASE.</p>
            <br>
            <h2><b>Interactions and Disputes between Owners and Renters</b></h2><br>
            <p>You agree that when you lend or borrow an item on the DixWix.com service, the lending transaction is solely between you and the lender and/or borrower, as applicable and that DixWix Technologies LLC is not a party to the lending transaction. DixWix Technologies LLC has the right, but not the obligation, to monitor, assist and/or resolve any dispute between lenders and borrowers – including without limitation and by way of example, by charging the full estimated value of borrowed items to the borrower's payment method in the event it is determined that the borrower has stolen, lost, or destroyed the lenders' items.</p>

            <p>In the event that a renter and a owner are unable to resolve a dispute between them directly, they may ask DixWix Technologies LLC to mediate the dispute. DixWix Technologies LLC may accept or reject such request to be a mediator at its sole discretion. If DixWix Technologies LLC accepts the request to act as a mediator, it may charge a fee of up to thirty percent (30%) of any amounts that DixWix Technologies LLC determines is payable by the renter to compensate the owner for any loss or damage to the applicable item. We will charge this amount to the renter in addition to any amounts the renter is required to pay to the owner, which may include the fees attributable to those days that the owner has been unable to lend the items to other renters.</p>
            
            <h4>Interactions and Disputes between Owners and Renters</h4>
            <p>Within a certain timeframe after completing a Lending Transaction, Owners and Renters can leave a public review ("Review") and submit a star rating ("Rating") about each other. Ratings or Reviews reflect the opinions of the individual User and do not reflect the opinions of DixWix Technologies LLC. Ratings and Reviews are not verified by DixWix Technologies LLC for accuracy and may be incorrect or misleading. Ratings and Reviews must be accurate and may not contain any offensive or defamatory language. Users are prohibited from manipulating the Ratings and Reviews system in any manner, such as instructing a third party to write a positive or negative Review about another User. Ratings and Reviews are part of a User's public profile and may also be surfaced elsewhere on the DixWix.com Service together with other relevant information such as number of Lending Transactions, number of cancellations, average response time, and any other information DixWix Technologies LLC considers to be relevant.</p>

            <h4>Fees and Payment Terms</h4>

            <h4>A. DixWix Technologies LLC Commission</h4>
            <p>DixWix Technologies LLC receives a commission from each of the Owner and the Renter for any and all Owner Transactions taking place through the DixWix.com Service ("DixWix Technologies LLC Commission"). For Owners, as DixWix Technologies LLC Commission, DixWix Technologies LLC will retain twenty five percent (25%) of the Hire Fee prior to its payment to Owner. For Renters, DixWix Technologies LLC will charge the Renter for the Hire Fee and the additional ten percent (10%) or twenty five percent (25%) depending on the items category of the Hire Fee as DixWix Technologies LLC Commission upon Owner's acceptance of Renter's request to enter into a Lending Transaction.</p>

            <h4>B. Commission Avoidance</h4>
            <p>You shall not engage in any practice which may avoid or lower the amount of DixWix Technologies LLC Commission that would otherwise have been payable had the Lending Transaction been completed using the DixWix.com Service (such practices collectively referred to as "Commission Avoidance"). Commission Avoidance includes, without limitation, entering into any Lending Transaction or otherwise coordinating to lend and borrow Items outside of the DixWix.com Service. In the event of engagement by any User(s) in any Commission Avoidance, such User(s) shall indemnify and hold harmless DixWix Technologies LLC in respect of any losses suffered by DixWix Technologies LLC as a result of such Commission Avoidance. In the event that you attempt to engage a User you met through the DixWix.com Service in a rental or transaction that does not use the DixWix.com Service, you are liable to pay a fine of up to the lesser of the DixWix Technologies LLC Commission or $200 as a penalty for doing so and DixWix Technologies LLC may terminate your account without liability to you.</p>

            <h4>C. Payment Methods</h4>
            <p>We accept various payment methods for the DixWix.com Service, including, but not limited to, Mastercard, Visa, and American Express. For any fees on the DixWix.com Service payable to DixWix Technologies LLC or other Users, DixWix Technologies LLC or its third-party payment processor will bill your payment method submitted in connection with the Lending Transaction or otherwise provided with your account. DixWix Technologies LLC will not fulfill any transaction without authorization validation of your purchase from your payment method.</p>

            <h4>D. Taxes</h4>
            <p>You acknowledge that you are solely responsible for payment of applicable taxes (if any) owed by you pursuant to your use of the DixWix.com Service.</p>

            <h4>E. Payment Processing Services</h4>
            <p>Payments made through the DixWix.com Service are processed by Stripe. You can read their full terms and conditions here. Payment processing services for Owners on DixWix Technologies LLC are provided by Stripe and are subject to the Stripe Connected Account Agreement, which includes the Stripe Terms of Service (collectively, the "Stripe Services Agreement"). By agreeing to these terms or continuing to operate as an Owner on DixWix Technologies LLC, you agree to be bound by the Stripe Services Agreement, as the same may be modified by Stripe from time to time. As a condition of DixWix Technologies LLC enabling payment processing services through Stripe, you agree to provide DixWix Technologies LLC accurate and complete information about you and your business, and you authorize DixWix Technologies LLC to share it and transaction information related to your use of the payment processing services provided by Stripe.</p>

            <h4>Interactions and Disputes between Owners and Renters</h4>
            <p>We care about the privacy of our Users. You understand that by using the DixWix.com Service, you consent to the collection, use and disclosure of your personally identifiable information and aggregate data as set forth in our Privacy Policy.</p>
                <br>
            <h2><b>Text Messaging</b></h2><br>
            <p>We offer you the chance to enroll to receive SMS/text messages from DixWix Technologies LLC. You may enroll to receive text messages about account-related news and alerts and/or offers for DixWix Technologies LLC products and services. By enrolling in DixWix Technologies LLC's SMS/text messaging service, you agree to receive text messages from DixWix Technologies LLC to your mobile phone number provided, and you certify that your mobile number provided is true and accurate and that you are authorized to enroll the designated mobile number to receive such text messages. You acknowledge and agree that the text messages may be sent using an automatic telephone dialing system and that standard message and data rates apply. Consent is not required as a condition of purchase.</p>

            <p>To unsubscribe from text messages at any time, email us at <a href="mailto:support@dixwix.com">support@dixwix.com</a>. You consent that following such a request to unsubscribe, you may receive one final text message from DixWix Technologies LLC confirming your request. For help, contact us via our <a href="/contact">contact page</a>.</p>
            
            <p>DixWix Technologies LLC cares about the integrity and security of your personal information. However, we cannot guarantee that unauthorized third parties will never be able to defeat our security measures or use your personal information for improper purposes. You acknowledge that you provide your personal information at your own risk.</p>
            <br>
            <h2><b>User Content</b></h2>
            <br>
            <p>It is our policy to respond to alleged infringement notices that comply with the Digital Millennium Copyright Act of 1998 ("DMCA").</p>

            <p>If you believe that your copyrighted work has been copied in a way that constitutes copyright infringement and is accessible via the DixWix.com Service, please notify DixWix Technologies LLC's copyright agent as set forth in the DMCA. For your complaint to be valid under the DMCA, you must provide the following information in writing:</p>

            <ul class="term-condition-list">
                <li>An electronic or physical signature of a person authorized to act on behalf of the copyright owner;</li>
                <li>Identification of the copyrighted work that you claim has been infringed;</li>
                <li>Identification of the material that is claimed to be infringing and where it is located on the DixWix.com Service;</li>
                <li>Information reasonably sufficient to permit DixWix Technologies LLC to contact you, such as your address, telephone number, and, email address;</li>
                <li>A statement that you have a good faith belief that use of the material in the manner complained of is not authorized by the copyright owner, its agent, or law; and</li>
                <li>A statement, made under penalty of perjury, that the above information is accurate, and that you are the copyright owner or are authorized to act on behalf of the owner.</li>
            </ul>

            <p>The above information must be submitted to the following DMCA Agent:</p>
            <ul class="term-condition-list">
                <li><b>Attn:</b> DMCA Notice DixWix Technologies LLC, Inc.</li>
                <li><b>Address:</b> 1805 Grande Chateau Ln Apex NC 27502</li>
                <li><b>Telephone:</b> (+1) 919 480 1467</li>
                <li><b>Email:</b> <a href="mailto:copyright@dixwix.com">copyright@dixwix.com</a></li>
            </ul>
            <p>Under federal law, if you knowingly misrepresent that online material is infringing, you may be subject to criminal prosecution for perjury and civil penalties, including without limitation monetary damages, court costs, and attorneys' fees.</p>
            <p>Please note that this procedure is exclusively for notifying DixWix Technologies LLC and its affiliates that your copyrighted material has been infringed. The preceding requirements are intended to comply with DixWix Technologies LLC's rights and obligations under the DMCA, including 17 U.S.C. §512(c), but do not constitute legal advice. It may be advisable to contact an attorney regarding your rights and obligations under the DMCA and other applicable laws.</p>

            <p>In accordance with the DMCA and other applicable law, DixWix Technologies LLC has adopted a policy of terminating, in appropriate circumstances, Users who are deemed to be repeat infringers. DixWix Technologies LLC may also at its sole discretion limit access to the DixWix.com Service and/or terminate the accounts of any Users who infringe any intellectual property rights of others, whether or not there is any repeat infringement.</p>
            <br>
            <h2><b>Third-Party Links and Information</b></h2>
            <br>
            <p>The DixWix.com Service may contain links to third-party materials that are not owned or controlled by DixWix Technologies LLC. DixWix Technologies LLC does not endorse or assume any responsibility for any such third-party sites, information, materials, products, or services. If you access a third-party website or service from the DixWix.com Service or share your User Content on or through any third-party website or service, you do so at your own risk, and you understand that these Terms and DixWix Technologies LLC's Privacy Policy do not apply to your use of such sites. You expressly relieve DixWix Technologies LLC from any and all liability arising from your use of any third-party website, service, or content, including without limitation User Content submitted by other Users. Additionally, your dealings with or participation in promotions of advertisers found on the DixWix.com Service, including, but not limited to, payment and delivery of goods, and any other terms (such as warranties) are solely between you and such advertisers. You agree that DixWix Technologies LLC shall not be responsible for any loss or damage of any sort relating to your dealings with such advertisers.</p>
            <br>
            <h2><b>Indemnity</b></h2>
            <br>
            <p>You agree to defend, indemnify and hold harmless DixWix Technologies LLC and its subsidiaries, agents, licensors, managers, and other affiliated companies, and their employees, contractors, agents, officers and directors, from and against any and all claims, damages, obligations, losses, liabilities, costs or debt, and expenses (including but not limited to attorney's fees) arising from:</p>

            <ul class="term-condition-list">
                <li>(i) your use of and access to the DixWix.com Service, including without limitation any data or content transmitted or received by you and your lending and/or borrowing of any Items;</li>
                <li>(ii) your violation of any term of these Terms, including without limitation your breach of any of the representations and warranties above;</li>
                <li>(iii) your violation of any third-party right, including without limitation any right of privacy or Intellectual Property Rights;</li>
                <li>(iv) your violation of any applicable law, rule or regulation;</li>
                <li>(v) User Content or any content that is submitted via your account including without limitation misleading, false, or inaccurate information;</li>
                <li>(vi) your willful misconduct; or</li>
                <li>(vii) any other party's access and use of the DixWix.com Service with your unique username, password or other appropriate security code.</li>
            </ul>
            <br>
            <h2><b>No Warranty</b></h2>
            <br>
            <p>The DixWix.com service is provided on an "as is" and "as available" basis. Use of the DixWix.com service is at your own risk. To the maximum extent permitted by applicable law, the DixWix.com service is provided without warranties of any kind, whether express or implied, including, but not limited to, implied warranties of merchantability, fitness for a particular purpose, or non-infringement. No advice or information, whether oral or written, obtained by you from DixWix Technologies LLC or through the DixWix.com service will create any warranty not expressly stated herein. Without limiting the foregoing, DixWix Technologies LLC, its subsidiaries, its affiliates, and its licensors do not warrant that the content is accurate, reliable or correct; that the DixWix.com service will meet your requirements; that the DixWix.com service will be available at any particular time or location, uninterrupted or secure; that any defects or errors will be corrected; or that the DixWix.com service is free of viruses or other harmful components. Any content downloaded or otherwise obtained through the use of the DixWix.com service is downloaded at your own risk and you will be solely responsible for any damage to your computer system or mobile device or loss of data that results from such download or your use of the DixWix.com service.</p>
            <p>DixWix Technologies LLC does not warrant, endorse, guarantee, or assume responsibility for any product or service advertised or offered by a third party through the DixWix.com service or any hyperlinked website or service, and DixWix Technologies LLC will not be a party to or in any way monitor any transaction between you and third-party providers of products or services.</p>
            <p>Federal law, some states, provinces and other jurisdictions do not allow the exclusion and limitations of certain implied warranties, so the above exclusions may not apply to you. This agreement gives you specific legal rights, and you may also have other rights which vary from state to state. The disclaimers and exclusions under this agreement will not apply to the extent prohibited by applicable law.</p>
            <br>
            <h2><b>Limitation of Liability</b></h2>
            <br>
            <p>To the maximum extent permitted by applicable law, in no event shall DixWix Technologies LLC, its affiliates, agents, directors, employees, suppliers or licensors be liable for any indirect, punitive, incidental, special, consequential or exemplary damages, including without limitation damages for loss of profits, goodwill, use, data or other intangible losses, arising out of or relating to the use of, or inability to use, this service. Under no circumstances will DixWix Technologies LLC be responsible for any damage, loss or injury resulting from hacking, tampering or other unauthorized access or use of the DixWix.com service or your account or the information contained therein.</p> <p>To the maximum extent permitted by applicable law, DixWix Technologies LLC assumes no liability or responsibility for any (i) errors, mistakes, or inaccuracies of content; (ii) personal injury or property damage, of any nature whatsoever, resulting from your access to or use of our service; (iii) any unauthorized access to or use of our secure servers and/or any and all personal information stored therein; (iv) any interruption or cessation of transmission to or from the DixWix.com service; (v) any bugs, viruses, trojan horses, or the like that may be transmitted to or through our service by any third party; (vi) any errors or omissions in any content or for any loss or damage incurred as a result of the use of any content posted, emailed, transmitted, or otherwise made available through the DixWix.com service; and/or (vii) user content or the defamatory, offensive, or illegal conduct of any third party. In no event shall DixWix Technologies LLC, its affiliates, agents, directors, employees, suppliers, or licensors be liable to you for any claims, proceedings, liabilities, obligations, damages, losses or costs in an amount exceeding the amount you paid to DixWix Technologies LLC hereunder or $100.00, whichever is greater.</p> <p>This limitation of liability section applies whether the alleged liability is based on contract, tort, negligence, strict liability, or any other basis, even if DixWix Technologies LLC has been advised of the possibility of such damage. The foregoing limitation of liability shall apply to the fullest extent permitted by law in the applicable jurisdiction.</p> <p>For any releases contained in these terms, if you are a California resident, you waive California Civil Code §1542, which says:</p> <p>"A general release does not extend to claims that the creditor or releasing party does not know or suspect to exist in his or her favor at the time of executing the release and that, if known by him or her, would have materially affected his or her settlement with the debtor or released party."</p> <p>Some states do not allow the exclusion or limitation of incidental or consequential damages, so the above limitations or exclusions may not apply to you. This agreement gives you specific legal rights, and you may also have other rights which vary from state to state. The disclaimers, exclusions, and limitations of liability under this agreement will not apply to the extent prohibited by applicable law.</p>
            <br>
            <h2><b>Governing Law, Arbitration, and Class Action/Jury Trial Waiver</b></h2>
            </br>
            <h4>A. Governing Law</h4>
            <p>You agree that: (i) the DixWix.com Service shall be deemed solely based in North Carolina; and (ii) the DixWix.com Service shall be deemed a passive one that does not give rise to personal jurisdiction over us, either specific or general, in jurisdictions other than North Carolina. These Terms shall be governed by the internal substantive laws of the State of North Carolina, without respect to its conflict of laws principles. The parties acknowledge that these Terms evidence a transaction involving interstate commerce. Notwithstanding the preceding sentences with respect to the substantive law, any arbitration conducted pursuant to the terms of these Terms shall be governed by the Federal Arbitration Act (9 U.S.C. §§ 1-16). The application of the United Nations Convention on Contracts for the International Sale of Goods is expressly excluded. You agree to submit to the personal jurisdiction of the federal and state courts located in Raleigh, North Carolina for any actions for which we retain the right to seek injunctive or other equitable relief in a court of competent jurisdiction to prevent the actual or threatened infringement, misappropriation or violation of our copyrights, trademarks, trade secrets, patents, or other intellectual property or proprietary rights, as set forth in the Arbitration provision below, including, but not limited to, any provisional relief required to prevent irreparable harm. You agree that San Francisco, California is the proper forum for any appeals of an arbitration award or for trial court proceedings in the event that the arbitration provision below is found to be unenforceable.</p>

            <h4>B. Arbitration</h4>
            <p>Read this section carefully because it requires the parties to arbitrate their disputes and limits the manner in which you can seek relief from DixWix Technologies LLC. For any dispute between you and DixWix Technologies LLC, you agree to first contact us at <a href="mailto:support@dixwix.com">support@dixwix.com</a> and attempt to resolve the dispute with us informally. In the unlikely event that DixWix Technologies LLC has not been able to resolve a dispute it has with you after sixty (60) days, we each agree to resolve any claim, dispute, or controversy (excluding any claims for injunctive or other equitable relief as provided below) arising out of or in connection with or relating to these Terms, or the breach or alleged breach thereof (collectively, "Claims"), by binding arbitration by JAMS, under the Optional Expedited Arbitration Procedures then in effect for JAMS, except as provided herein. JAMS may be contacted at <a href="http://www.jamsadr.com">www.jamsadr.com</a>. The arbitration will be conducted in Raleigh, North Carolina, unless you and DixWix Technologies LLC agree otherwise. If you are using the DixWix.com Service for commercial purposes, each party will be responsible for paying any JAMS filing, administrative, and arbitrator fees in accordance with JAMS rules, and the award rendered by the arbitrator shall include costs of arbitration, reasonable attorneys' fees and reasonable costs for expert and other witnesses. If you are an individual using the DixWix.com Service for non-commercial purposes: (i) JAMS may require you to pay a fee for the initiation of your case, unless you apply for and successfully obtain a fee waiver from JAMS; (ii) the award rendered by the arbitrator may include your costs of arbitration, your reasonable attorney's fees, and your reasonable costs for expert and other witnesses; and (iii) you may sue in a small claims court of competent jurisdiction without first engaging in arbitration, but this does not absolve you of your commitment to engage in the informal dispute resolution process. Any judgment on the award rendered by the arbitrator may be entered in the Small Claims court of Raleigh, North Carolina, USA. Nothing in this Section shall be deemed as preventing DixWix Technologies LLC from seeking injunctive or other equitable relief from the courts as necessary to prevent the actual or threatened infringement, misappropriation, or violation of our data security, Intellectual Property Rights, or other proprietary rights.</p>

            <h4>C. Class Action/Jury Trial Waiver</h4>
            <p>With respect to all persons and entities, regardless of whether they have obtained or used the DixWix.com Service for personal, commercial or other purposes, all claims must be brought in the parties' individual capacity, and not as a plaintiff or class member in any purported class action, collective action, private attorney general action or other representative proceeding. This waiver applies to class arbitration, and, unless we agree otherwise, the arbitrator may not consolidate more than one person's claims. You agree that, by entering into this agreement, you and DixWix Technologies LLC are each waiving the right to a trial by jury or to participate in a class action, collective action, private attorney general action, or other representative proceeding of any kind.</p>
            
            <br>
            <h2><b>Additional Mobile Applications Store Terms</b></h2>
            <br>
            <h4>A. Mobile Applications</h4>
            <p>We may make available software to access the DixWix.com Service via a mobile device ("Mobile Applications"). To use any Mobile Applications, you must have a mobile device that is compatible with the Mobile Applications. DixWix Technologies LLC does not warrant that the Mobile Applications will be compatible with your mobile device. You may use mobile data in connection with the Mobile Applications and may incur additional charges from your wireless provider for these services. You agree that you are solely responsible for any such charges. DixWix Technologies LLC hereby grants you a non-exclusive, non-transferable, revocable license to use a compiled code copy of the Mobile Applications for one DixWix Technologies LLC User Account on one mobile device owned or leased solely by you, for your personal use. You may not: (i) modify, disassemble, decompile or reverse engineer the Mobile Applications, except to the extent that such restriction is expressly prohibited by law; (ii) rent, lease, loan, resell, sublicense, distribute or otherwise transfer the Mobile Applications to any third party or use the Mobile Applications to provide time sharing or similar services for any third party; (iii) make any copies of the Mobile Applications; (iv) remove, circumvent, disable, damage or otherwise interfere with security-related features of the Mobile Applications, features that prevent or restrict use or copying of any content accessible through the Mobile Applications, or features that enforce limitations on use of the Mobile Applications; or (v) delete the copyright and other proprietary rights notices on the Mobile Applications. You acknowledge that DixWix Technologies LLC may from time to time issue upgraded versions of the Mobile Applications and may automatically electronically upgrade the version of the Mobile Applications that you are using on your mobile device. You consent to such automatic upgrading on your mobile device and agree that the terms and conditions of these Terms will apply to all such upgrades. Any third-party code that may be incorporated in the Mobile Applications is covered by the applicable open source or third-party license EULA, if any, authorizing use of such code. The foregoing license grant is not a sale of the Mobile Applications or any copy thereof, and DixWix Technologies LLC or its third-party partners or suppliers retain all right, title, and interest in the Mobile Applications (and any copy thereof). Any attempt by you to transfer any of the rights, duties or obligations hereunder, except as expressly provided for in these Terms, is void. DixWix Technologies LLC reserves all rights not expressly granted under these Terms. If the Mobile Applications are being acquired on behalf of the United States Government, then the following provision applies. The Mobile Applications will be deemed to be "commercial computer software" and "commercial computer software documentation," respectively, pursuant to DFAR Section 227.7202 and FAR Section 12.212, as applicable. Any use, reproduction, release, performance, display or disclosure of the DixWix.com Service and any accompanying documentation by the U.S. Government will be governed solely by these Terms and is prohibited except to the extent expressly permitted by these Terms. The Mobile Applications may not be exported or re-exported to certain countries or those persons or entities prohibited from receiving exports from the United States. In addition, the Mobile Applications may be subject to the import and export laws of other countries. You agree to comply with all United States and foreign laws related to use of the Mobile Applications and the DixWix.com Service.</p>
            <h4>B. Mobile Applications from Apple Mobile Applications Store</h4>
            <p>The following applies to any Mobile Applications you acquire from the Apple Mobile Applications Store ("Apple-Sourced Software"): You acknowledge and agree that these Terms are solely between you and DixWix Technologies LLC, not Apple, Inc. ("Apple") and that Apple has no responsibility for the Apple-Sourced Software or content thereof. Your use of the Apple-Sourced Software must comply with the Apple App Store Terms of Service. You acknowledge that Apple has no obligation whatsoever to furnish any maintenance and support services with respect to the Apple-Sourced Software. In the event of any failure of the Apple-Sourced Software to conform to any applicable warranty, you may notify Apple, and Apple will refund the purchase price for the Apple-Sourced Software to you; to the maximum extent permitted by applicable law, Apple will have no other warranty obligation whatsoever with respect to the Apple-Sourced Software, and any other claims, losses, liabilities, damages, costs or expenses attributable to any failure to conform to any warranty will be solely governed by these Terms and any law applicable to DixWix Technologies LLC as provider of the software. You acknowledge that Apple is not responsible for addressing any claims of you or any third party relating to the Apple-Sourced Software or your possession and/or use of the Apple-Sourced Software, including, but not limited to: (i) product liability claims; (ii) any claim that the Apple-Sourced Software fails to conform to any applicable legal or regulatory requirement; and (iii) claims arising under consumer protection or similar legislation; and all such claims are governed solely by these Terms and any law applicable to DixWix Technologies LLC as provider of the software. You acknowledge that, in the event of any third-party claim that the Apple-Sourced Software or your possession and use of that Apple-Sourced Software infringes that third party's intellectual property rights, DixWix Technologies LLC, not Apple, will be solely responsible for the investigation, defense, settlement and discharge of any such intellectual property infringement claim to the extent required by these Terms. You and DixWix Technologies LLC acknowledge and agree that Apple, and Apple's subsidiaries, are third-party beneficiaries of these Terms as relates to your license of the Apple-Sourced Software, and that, upon your acceptance of the terms and conditions of these Terms, Apple will have the right (and will be deemed to have accepted the right) to enforce these Terms as relates to your license of the Apple-Sourced Software against you as a third-party beneficiary thereof.</p>
            <h4>C. Mobile Applications from Google Play Store</h4>
            <p>The following applies to any Mobile Applications you acquire from the Google Play Store ("Google-Sourced Software"): (i) you acknowledge that these Terms are between you and DixWix Technologies LLC only, and not with Google, Inc. ("Google"); (ii) your use of Google-Sourced Software must comply with Google's then-current Google Play Store Terms of Service; (iii) Google is only a provider of the Google Play Store where you obtained the Google-Sourced Software; (iv) DixWix Technologies LLC, and not Google, is solely responsible for its Google-Sourced Software; (v) Google has no obligation or liability to you with respect to Google-Sourced Software or these Terms; and (vi) you acknowledge and agree that Google is a third-party beneficiary to these Terms as it relates to DixWix Technologies LLC's Google-Sourced Software.</p>
            <h4>General</h4>
            <h4>A. Assignment</h4>
            <p>These Terms, and any rights and licenses granted hereunder, may not be transferred or assigned by you, but may be assigned by DixWix Technologies LLC without restriction. Any attempted transfer or assignment in violation hereof shall be null and void.</p>
            <h4>B. Notification Procedures and Changes to these Terms</h4> 
            <p>DixWix Technologies LLC may provide notifications, whether such notifications are required by law or are for marketing or other business-related purposes, to you via email notice, written or hard copy notice, or through posting of such notice on our website, as determined by DixWix Technologies LLC in our sole discretion. DixWix Technologies LLC reserves the right to determine the form and means of providing notifications to our Users, provided that you may opt out of certain means of notification as described in these Terms. DixWix Technologies LLC is not responsible for any automatic filtering you or your network provider may apply to email notifications we send to the email address you provide us. DixWix Technologies LLC may, in its sole discretion, modify or update these Terms from time to time, and so you should review this page periodically. When we change these Terms in a material manner, we will update the ‘last modified' date at the bottom of this page and notify you that material changes have been made to these Terms. Your continued use of the DixWix.com Service after any such change constitutes your acceptance of the new Terms of Use. If you do not agree to any of these terms or any future Terms of Use, do not use or access (or continue to access) the DixWix.com Service.</p>
            <h4>C. Entire Agreement/Severability</h4>
            <p>These Terms, together with any amendments and any additional agreements you may enter into with DixWix Technologies LLC in connection with the DixWix.com Service, shall constitute the entire agreement between you and DixWix Technologies LLC concerning the DixWix.com Service. If any provision of these Terms is deemed invalid by a court of competent jurisdiction, the invalidity of such provision shall not affect the validity of the remaining provisions of these Terms, which shall remain in full force and effect, except that in the event of unenforceability of the universal Class Action/Jury Trial Waiver, the entire arbitration agreement shall be unenforceable.</p>
            <h4>D. No Waiver</h4>
            <p>No waiver of any term of these Terms shall be deemed a further or continuing waiver of such term or any other term, and DixWix Technologies LLC's failure to assert any right or provision under these Terms shall not constitute a waiver of such right or provision.</p>
            <h4>E. Contact</h4>
            <p>Please contact us at <a href="mailto:support@dixwix.com">support@dixwix.com</a> with any questions regarding these Terms.</p>

        </div>
        

        

        

        

    
    



    </div>
</section>



@include('common.wo_login.footer')