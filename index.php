<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>A Perfect Engineer</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">


    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            background-color:rgb(34, 33, 33);
            /* color: #333; */
            text-align: center;
            width: 100%;
            overflow-x: hidden;

        }

        .head-font {
            font-family: "Playfair Display", "Cinzel";
            color:#fff;
        }

        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 50px;
            position: absolute;
            width: 100%;
            top: 0;
            left: 0;
            border-bottom: 1px solid rgb(83, 83, 83);
        }

        .navbar .logo {
            font-size: 1.5rem;
            font-weight: 700;
            color: #fff;
        }

        .login-btn {
            background: linear-gradient(45deg, rgb(0, 0, 0), rgb(163, 31, 31));
            color: white;
            padding: 10px 18px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.9rem;
            margin-right: 40px;

        }

        .hero-section {
            display: flex;
            flex-direction: column;
            width: 100vw;
            color: white;
            align-items: center;
            justify-content: center;
            background-image: url('uploads/bg_main.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            height: 700px;
            padding-top: 110px;


        }

        .hero-section p {
            color: white;
        }

        h1 {
            font-size: 3.8rem;
            font-weight: bold;
            color: rgb(255, 255, 255);
            margin-bottom: 5px;

        }

        .highlight {
            color: rgb(255, 255, 255);
        }




        p {
            font-size: 1.2rem;
            max-width: 600px;
            /* margin: 10px auto; */
            line-height: 1.6;

        }

        .cta-buttons {
            margin-top: 20px;
            display: flex;
            gap: 35px;
            color: white;


        }

        .btn {
            display: inline-block;
            padding: 18px 34px;
            border-radius: 8px;
            font-size: 1.1rem;
            font-weight: bold;
            text-decoration: none;
            transition: 0.3s ease-in-out;
            border: none;
            cursor: pointer;
            background: linear-gradient(45deg, rgb(0, 0, 0), rgb(163, 31, 31));

        }

        .btn-col {
            color: white;
        }



        .btn:hover {
            transform: scale(1.1);
        }

        .footer-text {
            font-size: 0.8rem;
            color: #64748b;
            margin-top: 160px;
        }






        /* Container */
        .containerr {
            display: flex;
            width: 100%;
            /* margin: 10px 10px 0 10px ; */
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            /* border-radius: 10px; */
            overflow: hidden;
            height:600px
        }


        .column {
            width: 55%;
            padding: 15px 30px 15px 30px;
            padding-top:50px;
        }

        .left {
            background: rgb(63, 52, 55);
        }

        .right {
            background: rgb(63, 35, 42);
        }

        /* Titles */
        h2 {
            text-align: center;
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 20px;
            color: black;
        }

        h4 {
            font-size: 1.2rem;
            font-weight: 600;
            text-align: center;
            margin-bottom: 10px;
            color: black;
        }

        p {
            font-size: 1rem;
            line-height: 1.6;
            color: #fff;
        }

        .feature {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }

        .icon {
            background: #f8d7da;
            padding: 10px;
            border-radius: 8px;
            margin-right: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            min-width: 40px;
        }

        .icon i {
            font-size: 1.4rem;
            color: rgb(19, 18, 18);
        }


        /* Responsive */
        @media (max-width: 768px) {
            .container {
                flex-direction: column;
            }

            .column {
                width: 100%;
            }
        }


        




        .about-section {
            display: flex;
            justify-content: space-around;
            background-image: url('uploads/bg_main3.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            align-items: center;
            padding: 50px 20px 30px 20px;
            gap: 20px;
            width: 100vw;
            height: 550px;
            flex-wrap: wrap;
            color: #fff;

            /* margin-top: 30px; */
        }

        .about-content {
            flex: 1;
            max-width: 600px;
        }

        .about-content h1 {
            font-size: 2rem;
            margin-bottom: 20px;
            color: #fff;
        }

        .about-content h1 span {
            color: rgb(255, 255, 255);
        }

        .about-content p {
            font-size: 1rem;
            line-height: 1.6;
            margin-bottom: 20px;
            color: white;
        }

        .about-button {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #1e1e1e;
            border: 2px solid #fff;
            border-radius: 15px;
            color: #fff;
            font-size: 0.9rem;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .about-button:hover {
            background-color: #c3a760;
            color: #1e1e1e;
            border-color: #c3a760;
        }

        .about-image-container {
            flex: 1;
            max-width: 380px;
            position: relative;
            display: inline-block;
            border: 3px solid rgb(92, 91, 91);
            border-radius: 10px;
            overflow: hidden;
        }

        .about-image-container img {
            width: 100%;
            height: 450px;
            display: block;
            transition: transform 0.3s ease;
        }

        .about-image-container:hover img {
            transform: scale(0.95);

        }

        .management {
            color: rgb(255, 255, 255);
        }


        .footer {
            background-color: #121212;
            padding: 15px 40px;
            color: white;
            text-align: center;
        }

        .coll {
            color: #888;
        }

        .footer-contact {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            text-align: left;
            color: #888;
        }

        .contact-item {
            flex: 1;
            margin: 0 10px;
        }

        .contact-item strong {
            color: #d4af37;
        }

        .footer-line {
            border: none;
            border-top: 1px solid #444;
            margin: 20px 0;
        }

        .footer-bottom {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
        }

        .footer-bottom p,
        .social-icons {
            margin: 5px 10px;

        }

        .social-icons {
            margin: 10px 0;
            display: flex;
            justify-content: center;
            gap: 20px;
            /* Add spacing between icons */
        }

        .social-icons a {
            color: white;
            text-decoration: none;
            font-size: 24px;
            transition: color 0.3s ease, transform 0.3s ease;
            /* Smooth hover effect */
        }


        .social-icons a:hover {
            color: rgb(255, 255, 255);
            transform: scale(1.2);
            /* Slight zoom on hover */
        }

        .credit {
            text-align: right;
            color: #888;
            font-size: 14px;
        }

        @media (max-width: 768px) {
            .footer-contact {
                flex-direction: column;
                align-items: center;
                /* Center items on smaller screens */
            }

            .contact-item {
                margin: 10px 0;
                text-align: center;
            }

            .footer-bottom {
                flex-direction: column;
                text-align: center;
            }

            .social-icons {
                justify-content: center;
                /* Center align social icons */
            }

            .credit {
                text-align: center;
                margin-top: 10px;
            }
        }

        #col2 {
            color: #fff;
        }
    </style>
</head>

<body>

    <header class="navbar">
        <div class="logo ">Perfect Engineer</div>
        <a href="#aboutttt" class="login-btn">About Us</a>
    </header>


    <main class="hero-section">
        <h1>Connect with Your <span class="highlight"> परफेक्ट इंजीनियर</span></h1>
        <p>Whether you're a founder with a vision or a developer looking for an exciting opportunity, find your ideal
            match and build something amazing together.</p>

        <div class="cta-buttons">
            <a href="register.php" class="btn btn-col ">I'm a Founder</a>
            <a href="developer_register.php" class="btn  btn-col">I'm a Developer</a>
        </div>

        <p class="footer-text">Join our community of founders and developers building the next generation of startups.
        </p>
    </main>

    <div class="containerr">
        <!-- Left Side: Job Seekers -->
        <div class="column left">
            <h4 class="head-font">Got talent?</h4>
            <h2 class="head-font">Why job seekers can love us</h2>
            <div class="feature">
                <span class="icon"><i class="ph-bold ph-arrow-up-right"></i></span>
                <p>Connect directly with founders at top startups - no third-party recruiters allowed.</p>
            </div>
            <div class="feature">
                <span class="icon"><i class="ph-bold ph-file-text"></i></span>
                <p>Everything you need to know, all upfront. View salary, stock options, and more before applying.</p>
            </div>
            <div class="feature">
                <span class="icon"><i class="ph-bold ph-sparkle"></i></span>
                <p>Say goodbye to cover letters - your profile is all you need. One click to apply and you're done.</p>
            </div>
            <div class="feature">
                <span class="icon"><i class="ph-bold ph-star"></i></span>
                <p>Unique jobs at startups and tech companies you can't find anywhere else.</p>
            </div>
        </div>

        <!-- Right Side: Recruiters -->
        <div class="column right">
            <h4 class="head-font">Need talent?</h4>
            <h2 class="head-font">Why recruiters can love us</h2>
            <div class="feature">
                <span class="icon"><i class="ph-bold ph-arrow-up-right"></i></span>
                <p> Wide Candidate Reach: These platforms provide recruiters access to a large and diverse pool of
                    candidates, from entry-level developers to seasoned professionals.

                </p>
            </div>
            <div class="feature">
                <span class="icon"><i class="ph-bold ph-file-text"></i></span>
                <p>Detailed Candidate Information: Developers usually create detailed profiles that showcase their
                    skills, experience, portfolios, and certifications.
                </p>
            </div>
            <div class="feature">
                <span class="icon"><i class="ph-bold ph-sparkle"></i></span>
                <p>Cost Savings: Using a job platform reduces the need for expensive recruitment agencies or job fairs.
                </p>
            </div>
            <div class="feature">
                <span class="icon"><i class="ph-bold ph-calendar"></i></span>
                <p>Portfolio and GitHub Links: Many developers link their portfolios or GitHub profiles, giving
                    recruiters direct access to real examples of the candidate’s work, making the evaluation process
                    easier.
                </p>
            </div>
        </div>
    </div>

    <div class="abut" id="aboutttt">
        <section class="about-section">
            <div class="about-content ">
                <h1 class="head-font">About <span class="head-font">Our Web</span></h1>
                <p>
                    This platform available for job seekers, offering unique advantages. Some focus on professional
                    networking, allowing users to connect with recruiters and apply easily. Others provide a database of
                    job listings across industries, offering quick-apply options and company reviews to help candidates
                    make informed decisions.
                </p>
                <p>
                    For those interested in startups, there are platforms that connect candidates directly with company
                    founders and offer opportunities with equity options. Additionally, dedicated platforms for remote
                    work make it easier to find fully remote positions in fields like tech, marketing, and design.
                </p>

            </div>
            <div class="about-image-container">
                <img src="uploads/myself.jpg" alt="Hotel Room">
            </div>
        </section>
    </div>

    <div class="abutt2">
    <div class="container-3" >
        <h3 class="mt-5 mb-2 management fw-bold head-font text-center">
            Management <span class=" fw-bold head-font">Team</span>
        </h3>
        <div class="row p-3">
            <p>
                A project team consists , who oversees planning and execution, Team Members (developers, designers,
                analysts) who handle tasks, and Stakeholders, who provide input and approvals. Each member plays a key
                role in ensuring the project's success. Collaboration and clear communication are essential for
                achieving project goals.
            </p>
        </div>


    </div>

    <div class="col-lg-11   text-dark margin  mb-4">
        <div class="swiper mySwiper">

            <div class=" swiper-wrapper  d-flex flex-wrap justify-content-center ps-5" id="team-data">

                <div class="swiper-slide m-2 p-2" style="width: 17rem;">
                    <img src="uploads/main (2).jpg" width="150" height="350" class="card-img-top image rounded-3"
                        alt="Product Image">
                    <div class="card-body text-center mt-2">
                        <h5 class="card-title product_name head-font">[Rishiikesh]</h5>

                    </div>
                </div>
                <div class="swiper-slide m-2 p-2" style="width: 17rem;">
                    <img src="uploads/swapnaj1.jpg" width="150" height="350" class="card-img-top image rounded-3"
                        alt="Product Image">
                    <div class="card-body text-center mt-2">
                        <h5 class="card-title product_name head-font">[Swapnaj]</h5>

                    </div>
                </div>
                <div class="swiper-slide m-2 p-2" style="width: 17rem;">
                    <img src="uploads/myself.jpg" width="150" height="350" class="card-img-top image rounded-3"
                        alt="Product Image">
                    <div class="card-body text-center mt-2">
                        <h5 class="card-title product_name head-font">[Rushiikesh]</h5>

                    </div>
                </div>
                <div class="swiper-slide m-2 p-2" style="width: 17rem;">
                    <img src="uploads/d.jpg" width="150" height="350" class="card-img-top image rounded-3"
                        alt="Product Image">
                    <div class="card-body text-center mt-2">
                        <h5 class="card-title product_name head-font ">[Rushiikesh]</h5>

                    </div>
                </div>




            </div>
        </div>
    </div>


    </div>

   


    <footer class="footer">
        <div class="footer-contact">
            <div class="contact-item">
                <p class="coll"><strong href="tel: +7499045893" class="head-font">Phone</strong><br>+917499045893</p>
            </div>
            <div class="contact-item">
                <p class="coll"><strong href="" class="head-font">Email</strong><br>rushikeshjadhav3596@gmail.com</p>
            </div>
            <div class="contact-item">
                <p class="coll"><strong href="" class="head-font ">Address</strong><br>Sahyog Socity, Sant Tukaam Nagar,
                    Pune
                </p>
            </div>
        </div>
        <hr class="footer-line">
        <div class="footer-bottom">
            <p class="copyright coll">Rushikesh © 2025. All rights reserved</p>
            <div class="social-icons">

                <a href="facebook.com">
                    <i class="fab fa-facebook "></i>
                </a>
                <a href=" instagram.com">
                    <i class="fab fa-instagram"></i>
                </a>
                <a href="twitter.com">
                    <i class="fab fa-twitter"></i>
                        </a>

            </div>
            <p class="credit">Website made by Rushikesh <br>Website Maker</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>

    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>




    <script src="https://kit.fontawesome.com/9258825634.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-B0R/88s/J3Q5iXdzl5/u4+r1G7wG7s6956UJ4y/u/r46mE7v6P8E89/j" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>


</body>

</html>