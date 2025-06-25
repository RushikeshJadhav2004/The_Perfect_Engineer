<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'founder') {
    header("Location: login.php");
    exit();
}

require 'db_connect.php';
$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT name, email, role, company_name, company_website, company_size, funding_stage, equity_range, salary_range, role_description, required_tech_stack, experience_required FROM users WHERE id = :id");
$stmt->execute(['id' => $user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Founder Dashboard</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .dashboard-container {
            max-width: 1200px;
            margin: 100px auto;
            padding: 20px;
            background:rgb(32, 32, 32);
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-wrap: wrap;
            
        }

        .navbar {
            display: flex;
            justify-content: space-between;
            background: linear-gradient(45deg, rgb(0, 0, 0), rgb(163, 31, 31));
            padding: 15px;
            border-radius: 10px 10px 0 0;
            color: white;
            width: 100%;
        }

        .navbar a {
            color: white;
            text-decoration: none;
            margin: 0 15px;
            font-weight: bold;
        }

        .content {
            flex: 1;
            padding: 20px;
            color:#fff;
        }

        .sidebar {
            width: 300px;
            background: #fff;
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
        }

        .sidebar h3 {
            color: rgb(0, 0, 0);
            font-size: 20px;
            border-bottom: 2px solid #ddd;
            padding-bottom: 10px;
        }

        .sidebar p {
            font-size: 14px;
            margin: 8px 0;
        }



        .navbarr {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 50px;
            position: absolute;
            width: 100%;
            top: 0;
            left: 0;
            border-bottom: 1px solid rgb(83, 83, 83);
            margin-bottom: 20px;
            background:rgb(32, 32, 32);
        }

        .navbarr .logo {
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
            margin-right: 30px;

        }

        .post-idea-btn {
            /* position: absolute;
            bottom: 180px;
         left: 90px; */
            background: linear-gradient(45deg, rgb(0, 0, 0), rgb(163, 31, 31));
            color: white;
            border: none;
            padding: 8px 10px;
            font-size: 16px;
            cursor: pointer;
            border-radius: 5px;
        }

        /* .post-idea-btn:hover {
        background: #0056b3;
               } */

        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background: white;
            width: 60%;
            max-height: 80%;
            overflow-y: auto;
            border-radius: 8px;
            box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.2);
        }

        .modal-header {
            background: linear-gradient(45deg, rgb(0, 0, 0), rgb(163, 31, 31));
            color: white;
            padding: 8px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 10px;
            border-radius: 8px 8px 0 0;
        }

        .modal-body {
            padding: 20px;
            max-height: 500px;
            overflow-y: auto;
        }

        .section {
            margin-bottom: 20px;
            padding: 10px;
            border-radius: 8px;
            background: #f8f8f8;
        }

        .section h3 {
            margin-bottom: 10px;
            font-size: 18px;
        }

        .form-group {
            display: flex;
            gap: 10px;
        }

        .form-group input,
        .form-group select {
            flex: 1;
        }

        input,
        textarea,
        select {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .modal-footer {
            display: flex;
            justify-content: space-between;
            padding: 15px;
            background: #f1f1f1;
            border-radius: 0 0 8px 8px;
        }

        .cancel-btn,
        .post-btn {
            padding: 10px 20px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            font-size: 16px;
        }

        .cancel-btn {
            background: #ccc;
        }

        .postt {
            background: linear-gradient(45deg, rgb(0, 0, 0), rgb(163, 31, 31));
            color: white;
        }

        .post2,
        .post3,
        .post4 {
            background: linear-gradient(45deg, rgb(0, 0, 0), rgb(163, 31, 31));
            color: white;
        }

        .post4:hover {
            color: white;
        }

        .post2:hover {
            color: white;
        }

        .post3:hover {
            color: white;
        }

        .postt:hover {
            color: white;
        }



        .close-btn {
            /* background: #000; */
            color: white;
            padding-right: 4px;
            cursor: pointer;
            font-size: 15px;
            /* border: 1px solid #000;
        border-radius: 50%; */
        }
    </style>
</head>

<body>
    <header class="navbarr">
        <div class="logo head-font">परफेक्ट इंजीनियर</div>
        <a href="logout.php" class="login-btn"> Logout</a>
    </header>
    <div class="dashboard-container">
        <div class="navbar">
            <span>Welcome,
                <?php echo htmlspecialchars($user['name']); ?>!
            </span>
            <div>
                <a href="browse_developers.php">Browse Developers</a>
                <!-- <a href="startup_ideas.php">Startup Ideas</a> -->
                <a href="messages1.php">Messages</a>
                <a href="founder_inbox.php">Inbox</a>
            </div>
        </div>
        <div class="content">
            <h2>Recruiter Dashboard</h2>
            <p>You are logged in as a Recruiter.</p>
            <button type="button" class="btn postt" data-bs-toggle="modal" data-bs-target="#startupModal">
                Post Startup Ideas
            </button>
            <a class="btn post2" href="pending_applications.php">Pending</a>
            <a class="btn post2" href="accepted_applications.php">Accepted</a>
            <a class="btn post2" href="rejected_applications.php">Rejected</a>
        </div>




        <div class="sidebar">
            <h3>Company Profile &nbsp;&nbsp;&nbsp;&nbsp; <a href="founder_profile.php"><i
                        class="bi bi-pencil-square"></i></a></h3>
            <p><strong>Name:</strong>
                <?php echo htmlspecialchars($user['name']); ?>
            </p>
            <p><strong>Email:</strong>
                <?php echo htmlspecialchars($user['email']); ?>
            </p>
            <p><strong>Company:</strong>
                <?php echo htmlspecialchars($user['company_name']); ?>
            </p>
            <p><strong>Website:</strong> <a href="<?php echo htmlspecialchars($user['company_website']); ?>"
                    target="_blank">Visit Site </a></p>
            <p><strong>Size:</strong>
                <?php echo htmlspecialchars($user['company_size']); ?>
            </p>
            <p><strong>Funding:</strong>
                <?php echo htmlspecialchars($user['funding_stage']); ?>
            </p>
            <p><strong>Equity Range:</strong>
                <?php echo htmlspecialchars($user['equity_range']); ?>
            </p>
            <p><strong>Salary Range:</strong>
                <?php echo htmlspecialchars($user['salary_range']); ?>
            </p>
            <p><strong>Tech Stack:</strong>
                <?php echo htmlspecialchars($user['required_tech_stack']); ?>
            </p>
            <p><strong>Experience Required:</strong>
                <?php echo htmlspecialchars($user['experience_required']); ?> years
            </p>
            <p><strong>Role Description:</strong>
                <?php echo htmlspecialchars($user['role_description']); ?>
            </p>
        </div>
    </div>



    <!-- Startup Idea Modal -->
    <div class="modal fade" id="startupModal" tabindex="-1" aria-labelledby="startupModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl"> <!-- Increased size to large -->
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="startupModalLabel">Submit a Startup Idea</h5>
                    <button type="button" class="btn-close close-btn text-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Display Success/Error Messages -->
                    <?php if (isset($_SESSION['message'])): ?>
                    <div class="alert alert-success">
                        <?= $_SESSION['message']; unset($_SESSION['message']); ?>
                    </div>
                    <?php endif; ?>










                    <form action="post_idea.php" method="POST">
                        <div class="row">
                            <div class="col-md-6">
                                <!-- Title (Required) -->
                                <div class="mb-3">
                                    <label for="title" class="form-label">Startup Title <span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="title" id="title" class="form-control" required>
                                </div>

                                <!-- Short Description (Required) -->
                                <div class="mb-3">
                                    <label for="description" class="form-label">Short Description <span
                                            class="text-danger">*</span></label>
                                    <textarea name="description" id="description" class="form-control" rows="3"
                                        required></textarea>
                                </div>

                                <!-- Company Name -->
                                <div class="mb-3">
                                    <label for="company_name" class="form-label">Company Name</label>
                                    <input type="text" name="company_name" id="company_name" class="form-control">
                                </div>

                                <!-- Company Website -->
                                <div class="mb-3">
                                    <label for="company_website" class="form-label">Company Website</label>
                                    <input type="url" name="company_website" id="company_website" class="form-control">
                                </div>

                                <!-- Company Size -->
                                <div class="mb-3">
                                    <label for="company_size" class="form-label">Company Size</label>
                                    <select name="company_size" id="company_size" class="form-select">
                                        <option value="">Select Size</option>
                                        <option value="1-10">1-10</option>
                                        <option value="11-50">11-50</option>
                                        <option value="51-200">51-200</option>
                                        <option value="201+">201+</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <!-- Equity Range -->
                                <div class="mb-3">
                                    <label for="equity_range" class="form-label">Equity Range</label>
                                    <input type="text" name="equity_range" id="equity_range" class="form-control">
                                </div>

                                <!-- Salary Range -->
                                <div class="mb-3">
                                    <label for="salary_range" class="form-label">Salary Range</label>
                                    <input type="text" name="salary_range" id="salary_range" class="form-control">
                                </div>

                                <!-- Role -->
                                <div class="mb-3">
                                    <label for="role" class="form-label">Role</label>
                                    <input type="text" name="role" id="role" class="form-control">
                                </div>

                                <!-- Funding Stage -->
                                <div class="mb-3">
                                    <label for="funding_stage" class="form-label">Funding Stage</label>
                                    <select name="funding_stage" id="funding_stage" class="form-select">
                                        <option value="">Select Stage</option>
                                        <!-- <option value="Pre-Seed">Pre-Seed</option>
                                        <option value="Seed">Seed</option> -->
                                        <option value="Series A">Series A</option>
                                        <option value="Series B">Series B</option>
                                        <option value="Growth Stage">Growth Stage</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Detailed Idea -->
                        <div class="mb-3">
                            <label for="detailed_idea" class="form-label">Detailed Idea</label>
                            <textarea name="detailed_idea" id="detailed_idea" class="form-control" rows="4"></textarea>
                        </div>

                        <!-- Key Responsibilities -->
                        <div class="mb-3">
                            <label for="key_responsibilities" class="form-label">Key Responsibilities</label>
                            <textarea name="key_responsibilities" id="key_responsibilities" class="form-control"
                                rows="3"></textarea>
                        </div>

                        <!-- Ideal Candidate Profile -->
                        <div class="mb-3">
                            <label for="ideal_candidate_profile" class="form-label">Ideal Candidate Profile</label>
                            <textarea name="ideal_candidate_profile" id="ideal_candidate_profile" class="form-control"
                                rows="3"></textarea>
                        </div>

                        <!-- Tech Requirements -->
                        <div class="mb-3">
                            <label for="tech_requirements" class="form-label">Tech Requirements</label>
                            <textarea name="tech_requirements" id="tech_requirements" class="form-control"
                                rows="3"></textarea>
                        </div>

                        <!-- Experience -->
                        <div class="mb-3">
                            <label for="experience" class="form-label">Experience Level</label>
                            <input type="text" name="experience" id="experience" class="form-control">
                        </div>

                        <!-- Industry -->
                        <div class="mb-3">
                            <label for="industry" class="form-label">Industry</label>
                            <input type="text" name="industry" id="industry" class="form-control">
                        </div>

                        <!-- Modal Footer -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                Cancel
                            </button>

                            <button type="submit" class="btn postt">
                                Post Idea
                            </button>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>



    <script>
        function openModal() {
            document.getElementById("startupModal").style.display = "flex";
        }

        function closeModal() {
            document.getElementById("startupModal").style.display = "none";
        }
    </script>
    <!-- Bootstrap JS (for modal functionality) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>