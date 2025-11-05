<?php
require_once('../database/db.php');
session_start();

if (!$_SESSION["username"] == true) {
    header('location:index.php');
}

$user_type = 'cashier';
$username = $_SESSION['username']; // Assuming the cashier's name is stored in the session
?>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generate Bill</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            padding: 20px;
        }

        .container {
            max-width: 800px;
            margin: auto;
            background-color: #f7f7f7;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }

        .form-control {
            margin-bottom: 15px;
        }

        .btn-add {
            margin-bottom: 15px;
        }

        .text-right {
            text-align: right;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding: 10px;
            background-color: #007bff;
            color: white;
            border-radius: 5px;
        }

        .header h4 {
            margin: 0;
        }

        .header .btn-logout {
            background-color: #dc3545;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
        }

        #print-area {
            display: none;
        }

        /* Styles for printing */
        @media print {
            body * {
                visibility: hidden;
            }

            #print-area, #print-area * {
                visibility: visible;
            }

            #print-area {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
            }
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="header">
            <h4>Cashier: <?php echo $username; ?></h4>
            <form action="logout.php" method="post">
                <button name="out" class="btn btn-danger waves-effect waves-light">
                    <span class="btn-label"><i class="fa fa-power-off"></i></span> Logout
                </button>
            </form>
        </div>

        <h2 class="text-center">Generate Bill</h2>
        <form id="billForm">
            <!-- Customer Name -->
            <div class="form-group">
                <label for="customer_name">Customer Name</label>
                <input type="text" class="form-control" id="customer_name" placeholder="Enter customer's name" required>
            </div>

            <!-- Medicines and Prices -->
            <div id="medicines-list">
                <div class="form-row">
                    <div class="col-md-6">
                        <label for="medicine_1">Medicine Name</label>
                        <input type="text" class="form-control" id="medicine_1" placeholder="Enter medicine name" required>
                    </div>
                    <div class="col-md-4">
                        <label for="price_1">Price</label>
                        <input type="number" class="form-control price" id="price_1" placeholder="Enter price" required>
                    </div>
                    <div class="col-md-2">
                        <label>&nbsp;</label>
                        <button type="button" class="btn btn-danger btn-block btn-remove" disabled>Remove</button>
                    </div>
                </div>
            </div>

            <!-- Add More Medicines -->
            <button type="button" class="btn btn-success btn-add">Add More Medicine</button>

            <!-- Subtotal -->
            <div class="form-group text-right">
                <label for="subtotal">Subtotal</label>
                <input type="text" class="form-control text-right" id="subtotal" placeholder="Subtotal" readonly>
            </div>

            <!-- Generate Bill Button -->
            <div class="text-center">
                <button type="button" class="btn btn-primary" id="generateBillBtn">Generate Bill</button>
            </div>
        </form>
    </div>

    <!-- Hidden section for printing -->
    <div id="print-area">
        <h3>Customer Name: <span id="print-customer-name"></span></h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Medicine Name</th>
                    <th>Price</th>
                </tr>
            </thead>
            <tbody id="print-medicines"></tbody>
        </table>
        <h4 class="text-right">Subtotal: <span id="print-subtotal"></span></h4>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script>
        $(document).ready(function() {
            var medicineIndex = 1;

            // Function to update subtotal
            function updateSubtotal() {
                var subtotal = 0;
                $('.price').each(function() {
                    subtotal += parseFloat($(this).val()) || 0;
                });
                $('#subtotal').val(subtotal.toFixed(2));
            }

            // Add more medicine input fields
            $('.btn-add').click(function() {
                medicineIndex++;
                var medicineRow = `
                    <div class="form-row">
                        <div class="col-md-6">
                            <input type="text" class="form-control" name="medicine_${medicineIndex}" placeholder="Enter medicine name" required>
                        </div>
                        <div class="col-md-4">
                            <input type="number" class="form-control price" name="price_${medicineIndex}" placeholder="Enter price" required>
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-danger btn-block btn-remove">Remove</button>
                        </div>
                    </div>`;
                $('#medicines-list').append(medicineRow);
            });

            // Remove a medicine input field
            $(document).on('click', '.btn-remove', function() {
                $(this).closest('.form-row').remove();
                updateSubtotal();
            });

            // Update subtotal when price changes
            $(document).on('input', '.price', function() {
                updateSubtotal();
            });

            // Handle Generate Bill button click
            $('#generateBillBtn').click(function() {
                // Check if all fields are filled
                var allFilled = true;
                if ($('#customer_name').val() === '') {
                    allFilled = false;
                }

                $('#medicines-list .form-row').each(function() {
                    var medicineName = $(this).find('input[type="text"]').val();
                    var medicinePrice = $(this).find('input[type="number"]').val();
                    if (medicineName === '' || medicinePrice === '') {
                        allFilled = false;
                    }
                });

                if (!allFilled) {
                    alert('Please fill in all fields before generating the bill.');
                    return;
                }

                // Populate the print area with the entered data
                $('#print-customer-name').text($('#customer_name').val());

                var medicinesHTML = '';
                $('#medicines-list .form-row').each(function() {
                    var medicineName = $(this).find('input[type="text"]').val();
                    var medicinePrice = $(this).find('input[type="number"]').val();
                    medicinesHTML += `<tr><td>${medicineName}</td><td>${medicinePrice}</td></tr>`;
                });
                $('#print-medicines').html(medicinesHTML);

                $('#print-subtotal').text($('#subtotal').val());

                // Show the print area, trigger print, then hide it again
                $('#print-area').show();
                window.print();
                $('#print-area').hide();
            });
        });
    </script>

</body>

</html>
