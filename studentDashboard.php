<?php

session_start();

include "connection.php";


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $projectName = $_POST["projectName"];
    $projectLeader = $_SESSION["user_id"];
    $projectMember1 = $_POST["projectMember1"];
    $projectMember2 = $_POST["projectMember2"];
    $projectMember3 = $_POST["projectMember3"];
    $category = $_POST["category"];
    $initial_budget = $_POST["initial_budget"];
    $file_path = $_FILES["zipFile"]["name"];

        
    $sql = "INSERT INTO project_details (project_name, project_leader_id, project_member_1_name, project_member_2_name, project_member_3_name, category, stage_1, stage_2, stage_3, initial_budget, final_budget, project_path)
            VALUES ('$projectName', '$projectLeader', '$projectMember1', '$projectMember2','$projectMember3', '$category', 0, 0, 0, '$initial_budget', NULL, '$file_path')";
        

    if ($conn->query($sql) === TRUE) {
        echo '<script>alert("New project added successfully!"); window.location.href = "studentDashboard.php";</script>';
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$userId = $_SESSION["user_id"];
$selectQuery1 = "
    SELECT
        project_details.project_name,
        project_details.project_member_1_name,
        project_details.project_member_2_name,
        project_details.project_member_3_name,
        project_details.category,
        project_details.stage_1,
        project_details.stage_2,
        project_details.stage_3,
        project_details.initial_budget,
        project_details.project_path
    FROM
        users
    LEFT JOIN
        project_details ON users.user_id = project_details.project_leader_id
    WHERE
        users.user_id = $userId
";

$selectResult1 = $conn->query($selectQuery1);

if ($selectResult1 === false) {
    die("Error retrieving user and project information: " . $conn->error);
}

$row = $selectResult1->fetch_assoc();

if ($row) {
    $project_name = $row["project_name"];
    $project_member_id = $_SESSION["user_id"];
    $project_member_1_name = $row["project_member_1_name"];
    $project_member_2_name = $row["project_member_2_name"];
    $project_member_3_name = $row["project_member_3_name"];
    $category = $row["category"];
    $initial_budget = $row["initial_budget"];
    $project_path = $row["project_path"];

    $stage_1 = $row["stage_1"];
    $stage_2 = $row["stage_2"];
    $stage_3 = $row["stage_3"];

} else {
    echo "No record found for the given user_id.";
}


$selectQuery = "SELECT name FROM users WHERE user_id = $userId";
$selectResult = $conn->query($selectQuery);

if ($selectResult === false) {
    die("Error retrieving user information: " . $conn->error);
}

$row = $selectResult->fetch_assoc();
$name = $row["name"];


$conn->close();
?>



<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Welcome Student</title>
  <link rel="icon" href="https://seeklogo.com/images/I/independent-university-logo-776F5F3A69-seeklogo.com.png">

  <script src="https://cdn.tailwindcss.com"></script>

  <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.0/flowbite.min.css" rel="stylesheet" />

  <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.0/flowbite.min.js" defer></script>

</head>
<body>

    <nav class="fixed top-0 z-50 w-full bg-white border-b border-gray-200">
    <div class="px-3 py-3 lg:px-5 lg:pl-3">
        <div class="flex items-center justify-between">
        <div class="flex items-center justify-start rtl:justify-end">
            <button data-drawer-target="logo-sidebar" data-drawer-toggle="logo-sidebar" aria-controls="logo-sidebar" type="button" class="inline-flex items-center p-2 text-sm text-gray-500 rounded-lg sm:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200">
                <span class="sr-only">Open sidebar</span>
                <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path clip-rule="evenodd" fill-rule="evenodd" d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z"></path>
                </svg>
            </button>
            <a href="index.php" class="flex ms-2 md:me-24">
            <img src="https://seeklogo.com/images/I/independent-university-logo-776F5F3A69-seeklogo.com.png" class="h-8 me-3" alt="FlowBite Logo" />
            <span class="self-center text-xl font-semibold sm:text-2xl whitespace-nowrap">IUB 360</span>
            </a>
        </div>
        <div class="flex items-center">
            <div class="flex items-center ms-3">
                <div>
                <button type="button" class="flex text-sm bg-gray-800 rounded-full focus:ring-4 focus:ring-gray-300" aria-expanded="false" data-dropdown-toggle="dropdown-user">
                    <span class="sr-only">Open user menu</span>
                    <img class="w-8 h-8 rounded-full" src="https://flowbite.com/docs/images/people/profile-picture-5.jpg" alt="user photo">
                </button>
                </div>
                <div class="z-50 hidden my-4 text-base list-none bg-white divide-y divide-gray-100 rounded shadow" id="dropdown-user">
                <div class="px-4 py-3" role="none">
                    <p class="text-sm text-gray-900" role="none">
                    <?php
                            if (isset($_SESSION["name"])) {
                                echo $_SESSION["name"];
                            }
                            else {
                                echo "Name";
                            }
                    ?>
                    </p>
                    <p class="text-sm font-medium text-gray-900 truncate" role="none">
                        <?php
                            if (isset($_SESSION["email"])) {
                                echo $_SESSION["email"];
                            }
                            else {
                                echo "Email";
                            }
                        ?>
                    </p>
                </div>
                <ul class="py-1" role="none">
                    <li>
                    <a href="studentDashboard.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">Dashboard</a>
                    </li>
                    <li>
                    <a href="studentManageAccount.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">Manage</a>
                    </li>
                    <li>
                    <a href="logout.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">Sign out</a>
                    </li>
                </ul>
                </div>
            </div>
            </div>
        </div>
    </div>
    </nav>

    <aside id="logo-sidebar" class="fixed top-0 left-0 z-40 w-64 h-screen pt-20 transition-transform -translate-x-full bg-white border-r border-gray-200 sm:translate-x-0" aria-label="Sidebar">
    <div class="h-full px-3 pb-4 overflow-y-auto bg-white">
        <ul class="space-y-2 font-medium">
            <li>
                <a href="studentDashboard.php" class="flex items-center p-2 text-gray-900 rounded-lg group">
                <svg class="w-5 h-5 text-gray-500 transition duration-75" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 22 21">
                    <path d="M16.975 11H10V4.025a1 1 0 0 0-1.066-.998 8.5 8.5 0 1 0 9.039 9.039.999.999 0 0 0-1-1.066h.002Z"/>
                    <path d="M12.5 0c-.157 0-.311.01-.565.027A1 1 0 0 0 11 1.02V10h8.975a1 1 0 0 0 1-.935c.013-.188.028-.374.028-.565A8.51 8.51 0 0 0 12.5 0Z"/>
                </svg>
                <span class="ms-3">Dashboard</span>
                </a>
            </li>
            <li>
                <a href="studentProjects.php" class="flex items-center p-2 text-gray-900 rounded-lg group">
                <svg class="flex-shrink-0 w-5 h-5 text-gray-500 transition duration-75" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 18 18">
                    <path d="M6.143 0H1.857A1.857 1.857 0 0 0 0 1.857v4.286C0 7.169.831 8 1.857 8h4.286A1.857 1.857 0 0 0 8 6.143V1.857A1.857 1.857 0 0 0 6.143 0Zm10 0h-4.286A1.857 1.857 0 0 0 10 1.857v4.286C10 7.169 10.831 8 11.857 8h4.286A1.857 1.857 0 0 0 18 6.143V1.857A1.857 1.857 0 0 0 16.143 0Zm-10 10H1.857A1.857 1.857 0 0 0 0 11.857v4.286C0 17.169.831 18 1.857 18h4.286A1.857 1.857 0 0 0 8 16.143v-4.286A1.857 1.857 0 0 0 6.143 10Zm10 0h-4.286A1.857 1.857 0 0 0 10 11.857v4.286c0 1.026.831 1.857 1.857 1.857h4.286A1.857 1.857 0 0 0 18 16.143v-4.286A1.857 1.857 0 0 0 16.143 10Z"/>
                </svg>
                <span class="flex-1 ms-3 whitespace-nowrap">Projects</span>
                </a>
            </li>
            <li>
                <a href="studentMessages.php" class="flex items-center p-2 text-gray-900 rounded-lg group">
                <svg class="flex-shrink-0 w-5 h-5 text-gray-500 transition duration-75" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                    <path d="m17.418 3.623-.018-.008a6.713 6.713 0 0 0-2.4-.569V2h1a1 1 0 1 0 0-2h-2a1 1 0 0 0-1 1v2H9.89A6.977 6.977 0 0 1 12 8v5h-2V8A5 5 0 1 0 0 8v6a1 1 0 0 0 1 1h8v4a1 1 0 0 0 1 1h2a1 1 0 0 0 1-1v-4h6a1 1 0 0 0 1-1V8a5 5 0 0 0-2.582-4.377ZM6 12H4a1 1 0 0 1 0-2h2a1 1 0 0 1 0 2Z"/>
                </svg>
                <span class="flex-1 ms-3 whitespace-nowrap">Messages</span>
                </a>
            </li>
            <li>
                <a href="studentManageAccount.php" class="flex items-center p-2 text-gray-900 rounded-lg group">
                <svg class="flex-shrink-0 w-5 h-5 text-gray-500 transition duration-75" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 18">
                    <path d="M14 2a3.963 3.963 0 0 0-1.4.267 6.439 6.439 0 0 1-1.331 6.638A4 4 0 1 0 14 2Zm1 9h-1.264A6.957 6.957 0 0 1 15 15v2a2.97 2.97 0 0 1-.184 1H19a1 1 0 0 0 1-1v-1a5.006 5.006 0 0 0-5-5ZM6.5 9a4.5 4.5 0 1 0 0-9 4.5 4.5 0 0 0 0 9ZM8 10H5a5.006 5.006 0 0 0-5 5v2a1 1 0 0 0 1 1h11a1 1 0 0 0 1-1v-2a5.006 5.006 0 0 0-5-5Z"/>
                </svg>
                <span class="flex-1 ms-3 whitespace-nowrap">Manage Account</span>
                </a>
            </li>
            <li>
                <a href="logout.php" class="flex items-center p-2 text-gray-900 rounded-lg group">
                <svg class="flex-shrink-0 w-5 h-5 text-gray-500 transition duration-75" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 16">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 8h11m0 0L8 4m4 4-4 4m4-11h3a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2h-3"/>
                </svg>
                <span class="flex-1 ms-3 whitespace-nowrap">Sign Out</span>
                </a>
            </li>
        </ul>
    </div>
    </aside>

    <div class="py-20 px-16 sm:ml-64">
        <div class="p-4 border-2 border-gray-200 border-dashed rounded-lg mt-14">
            <div class="flex items-center justify-center h-fit mb-4 rounded bg-gray-50 ">
                <p class="text-2xl text-black"> Welcome 
                    <?php
                        if (isset($_SESSION["name"])) {
                            echo $_SESSION["name"];
                        }
                        else {
                            echo "User Not Found";
                        }
                        echo "!";
                        echo "<br>";
                        if (isset($_SESSION["email"])) {
                            echo "Email: " . $_SESSION["email"];
                        }
                        else {
                            echo "Email: No User Found";
                        }
                    ?>
                </p>
            </div>
        </div>
    </div>
    <div class="py-20 px-16 sm:ml-64">
    
    <div class="p-4 border-2 border-gray-200 border-dashed rounded-lg mb-8">
        <h2 class="text-xl font-semibold mb-4">Project Information Form</h2>
        <form action="" method="post" enctype="multipart/form-data">
            <div class="mb-4">
                <label for="projectName" class="block text-sm font-medium text-gray-700">Project Name:</label>
                <input type="text" id="projectName" name="projectName" value="<?php echo $project_name; ?>" class="w-full px-3 py-2 border rounded-md border-gray-300">
            </div>

            <div class="mb-4">
                <label for="projectLeader" class="block text-sm font-medium text-gray-700">Project Leader:</label>
                <input type="text" id="projectLeader" name="projectLeader" value="<?php echo $name; ?>" class="w-full px-3 py-2 border rounded-md border-gray-300">
            </div>

            <div class="mb-4">
                <label for="projectMember1" class="block text-sm font-medium text-gray-700">Project Member 1:</label>
                <input type="text" id="projectMember1" name="projectMember1" value="<?php echo $project_member_1_name; ?>" class="w-full px-3 py-2 border rounded-md border-gray-300">
            </div>
            <div class="mb-4">
                <label for="projectMember2" class="block text-sm font-medium text-gray-700">Project Member 2:</label>
                <input type="text" id="projectMember2" name="projectMember2" value="<?php echo $project_member_2_name; ?>" class="w-full px-3 py-2 border rounded-md border-gray-300">
            </div>
            <div class="mb-4">
                <label for="projectMember3" class="block text-sm font-medium text-gray-700">Project Member 3:</label>
                <input type="text" id="projectMember3" name="projectMember3" value="<?php echo $project_member_3_name; ?>" class="w-full px-3 py-2 border rounded-md border-gray-300">
            </div>
            <div class="mb-4">
                <label for="category" class="block text-sm font-medium text-gray-700">Project Category: </label>
                <input type="text" id="category" name="category" value="<?php echo $category; ?>"  class="w-full px-3 py-2 border rounded-md border-gray-300">
            </div>
            <div class="mb-4">
                <label for="initial_budget" class="block text-sm font-medium text-gray-700">Initial Budget: </label>
                <input type="number" id="initial_budget" name="initial_budget" placeholder="0" value="<?php echo $initial_budget; ?>" class="w-full px-3 py-2 border rounded-md border-gray-300">
            <div class="mb-4">
                <label for="zipFile" class="block text-sm font-medium text-gray-700">Upload Project:</label>
                <input type="file" id="zipFile" name="zipFile" accept=".zip, .rar, .pdf" value="<?php echo $project_path; ?>" class="w-full border rounded-md border-gray-300">
            </div>
            <div>
                <input type="submit" value="Submit" name="project_info" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-700">
            </div>
        </form>
    </div>


</div>



</body>
</html>
