<?php
session_start();

include('conn.php');

require '../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

if(isset($_POST['save_excel_data']))
{
    $fileName = $_FILES['import_file']['name'];
    $file_ext = pathinfo($fileName, PATHINFO_EXTENSION);

    $allowed_ext = ['xls','csv','xlsx'];

    if(in_array($file_ext, $allowed_ext))
    {
        $inputFileNamePath = $_FILES['import_file']['tmp_name'];
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($inputFileNamePath);
        $data = $spreadsheet->getActiveSheet()->toArray();

        $count = "0";

        
        foreach($data as $row)
        {
            if ($count > 0) {

// Assuming $row is your fetched data from the database
$lrn = mysqli_real_escape_string($conn, $row[0]);
$lname = mysqli_real_escape_string($conn, $row[1]);
$fname = mysqli_real_escape_string($conn, $row[2]);
$mname = mysqli_real_escape_string($conn, $row[3]);
$religion = mysqli_real_escape_string($conn, $row[4]);
$language = mysqli_real_escape_string($conn, $row[5]);
$nationality = mysqli_real_escape_string($conn, $row[6]);
$age = mysqli_real_escape_string($conn, $row[7]);
$gender = mysqli_real_escape_string($conn, $row[8]);
$rawDob = $row[9]; // Assuming this is the date field index
$dob = date('Y-m-d', strtotime($rawDob)); // Convert date format as needed
$contact = mysqli_real_escape_string($conn, $row[10]);
$placeOfBirth = mysqli_real_escape_string($conn, $row[11]);
$address = mysqli_real_escape_string($conn, $row[12]);
$fatherLastName = mysqli_real_escape_string($conn, $row[13]);
$fatherFirstName = mysqli_real_escape_string($conn, $row[14]);
$fatherMiddleName = mysqli_real_escape_string($conn, $row[15]);
$fatherContact = mysqli_real_escape_string($conn, $row[16]);
$fatherAge = mysqli_real_escape_string($conn, $row[17]);
$fatherOccupation = mysqli_real_escape_string($conn, $row[18]);
$fatherEducationalAttainment = mysqli_real_escape_string($conn, $row[19]);
$fatherWorkAddress = mysqli_real_escape_string($conn, $row[20]);
$fatherEmail = mysqli_real_escape_string($conn, $row[21]);
$fatherAddress = mysqli_real_escape_string($conn, $row[22]);
$motherLastName = mysqli_real_escape_string($conn, $row[23]);
$motherFirstName = mysqli_real_escape_string($conn, $row[24]);
$motherMiddleName = mysqli_real_escape_string($conn, $row[25]);
$motherContact = mysqli_real_escape_string($conn, $row[26]);
$motherAge = mysqli_real_escape_string($conn, $row[27]);
$motherOccupation = mysqli_real_escape_string($conn, $row[28]);
$motherEducationalAttainment = mysqli_real_escape_string($conn, $row[29]);
$motherWorkAddress = mysqli_real_escape_string($conn, $row[30]);
$motherEmail = mysqli_real_escape_string($conn, $row[31]);
$motherAddress = mysqli_real_escape_string($conn, $row[32]);
$guardianLastName = mysqli_real_escape_string($conn, $row[33]);
$guardianFirstName = mysqli_real_escape_string($conn, $row[34]);
$guardianMiddleName = mysqli_real_escape_string($conn, $row[35]);
$guardianAddress = mysqli_real_escape_string($conn, $row[36]);
$guardianContact = mysqli_real_escape_string($conn, $row[37]); // Assuming this is the last index



            
                $studentQuery = "INSERT INTO students (lrn, lname, fname, mname, religion, language, nationality, gender, dob, contact, pob, address, 
                flname, ffname, fmname, fcontact, fage, foccu, 
                feduc, foffice, femail, mlname, mfname, 
                mmname, mcontact, mage, moccu, meduc, 
                moffice, memail, glname, gfname, gmname, 
                gaddress, gcontact) 
            VALUES ('$lrn', '$lname', '$fname', '$mname', '$religion', '$language', '$nationality', '$gender', '$dob', '$contact', '$placeOfBirth', '$address', 
                '$fatherLastName', '$fatherFirstName', '$fatherMiddleName', '$fatherContact', '$fatherAge', '$fatherOccupation', 
                '$fatherEducationalAttainment', '$fatherWorkAddress', '$fatherEmail', '$motherLastName', '$motherFirstName', 
                '$motherMiddleName', '$motherContact', '$motherAge', '$motherOccupation', '$motherEducationalAttainment', 
                '$motherWorkAddress', '$motherEmail', '$guardianLastName', '$guardianFirstName', '$guardianMiddleName', 
                '$guardianAddress', '$guardianContact')";
            

                $result = mysqli_query($conn, $studentQuery);
                $msg = true;
            } else {
                $count = "1";
            }
            
        }

        if(isset($msg))
        {
            $_SESSION['message'] = "Successfully Imported";
            header('Location: ../manage_studentsRec.php');
            exit(0);
        }
        else
        {
            $_SESSION['message'] = "Not Imported";
            header('Location: ../manage_studentsRec.php');
            exit(0);
        }
    }
    else
    {
        $_SESSION['message'] = "Invalid File";
        header('Location: ../manage_studentsRec.php');
        exit(0);
    }
}
?>