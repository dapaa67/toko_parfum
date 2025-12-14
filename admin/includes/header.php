<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? htmlspecialchars($pageTitle) : 'Admin Dashboard'; ?> - ParfumMy</title>
    <link href="../css/output.css" rel="stylesheet">
    <link href="../css/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="admin.css">
    <script defer src="../js/alpine.min.js"></script>
</head>
<body class="bg-gray-50 font-sans">

<?php include 'sidebar.php'; ?>

<main class="ml-64 min-h-screen">
    <div class="px-4 pb-4">
