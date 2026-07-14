<?php
require('fpdf/fpdf.php');
include_once 'database.php';
include_once 'classes.php';


class PDF extends FPDF
{
     function Header()
     {
          $this->Image('images/logo.png', 10, 6, 50);
          $this->SetFont('Arial', '', 10);
          $this->Cell(0, 5, '299 Doon, Kitchener, Canada', 0, 1, 'R');
          $this->Cell(0, 5, 'Email: info@thecomicshop.com', 0, 1, 'R');
          $this->Cell(0, 5, 'Phone: +1 (123) 456-7890', 0, 1, 'R');
          $this->Ln(10);
          $this->SetFont('Arial', 'B', 16);
          $this->Cell(0, 10, 'Invoice Details', 0, 1, 'C');
          $this->Ln(5);
          $this->horizontalLine();
          $this->Ln(10);
     }

     function Footer()
     {
          $this->SetY(-10);
          $this->SetFont('Arial', 'I', 8);
          $this->SetFillColor(44, 62, 80);
          $this->SetTextColor(255, 255, 255);

          $this->SetX(0);
          $this->Cell(210, 10, 'Page ' . $this->PageNo(), 0, 0, 'C', true);
     }

     function CustomerInfo($customerName, $customerEmail, $orderDate, $customerAddress, $mobileNumber, $zipCode)
     {
          $this->SetFont('Arial', 'B', 12);
          $this->SetFillColor(255, 0, 0);
          $this->SetTextColor(255, 255, 255);
          $this->Cell(0, 8, 'CUSTOMER INFORMATION', 0, 1, 'L', true);
          $this->SetTextColor(0, 0, 0);
          $this->SetFont('Arial', '', 10);
          $labelWidth = 40;
          $valueWidth = 150;

          $this->SetFont('Arial', 'B', 10);
          $this->Cell($labelWidth, 8, 'Name:', 0, 0);
          $this->SetFont('Arial', '', 10);
          $this->Cell($valueWidth, 8, $customerName, 0, 1);

          $this->SetFont('Arial', 'B', 10);
          $this->Cell($labelWidth, 8, 'Email:', 0, 0);
          $this->SetFont('Arial', '', 10);
          $this->Cell($valueWidth, 8, $customerEmail, 0, 1);

          $this->SetFont('Arial', 'B', 10);
          $this->Cell($labelWidth, 8, 'Order Date:', 0, 0);
          $this->SetFont('Arial', '', 10);
          $this->Cell($valueWidth, 8, $orderDate, 0, 1);

          $this->SetFont('Arial', 'B', 10);
          $this->Cell($labelWidth, 8, 'Address:', 0, 0);
          $this->SetFont('Arial', '', 10);
          $this->Cell($valueWidth, 8, $customerAddress, 0, 1);

          $this->SetFont('Arial', 'B', 10);
          $this->Cell($labelWidth, 8, 'Mobile Number:', 0, 0);
          $this->SetFont('Arial', '', 10);
          $this->Cell($valueWidth, 8, $mobileNumber, 0, 1);

          $this->SetFont('Arial', 'B', 10);
          $this->Cell($labelWidth, 8, 'Zip Code:', 0, 0);
          $this->SetFont('Arial', '', 10);
          $this->Cell($valueWidth, 8, $zipCode, 0, 1);

          $this->Ln(10);

          $this->horizontalLine();
     }

     function ProductTable($header, $data)
     {
          $this->SetFont('Arial', 'B', 10);
          $this->SetFillColor(255, 0, 0);
          $this->SetTextColor(255, 255, 255);
          $widths = array(125, 20, 20, 25);
          for ($i = 0; $i < count($header); $i++) {
               $this->Cell($widths[$i], 7, $header[$i], 1, 0, 'C', true);
          }
          $this->Ln();
          $this->SetFont('Arial', '', 10);
          $this->SetTextColor(0, 0, 0);

          foreach ($data as $row) {
               $i = 0;
               $this->Cell($widths[$i++], 7, $row['product_name'], 'LR');
               $this->Cell($widths[$i++], 7, $row['product_quantity'], 'LR', 0, 'C');
               $this->Cell($widths[$i++], 7, '$' . number_format($row['product_price'], 2), 'LR', 0, 'R');
               $this->Cell($widths[$i++], 7, '$' . number_format($row['product_quantity'] * $row['product_price'], 2), 'LR', 0, 'R');
               $this->Ln();
          }

          $this->Cell(array_sum($widths), 0, '', 'T');
          $this->Ln(10);
     }

     function OrderSummary($subtotal, $taxes, $totalAmount, $totalItems)
     {
          $height = 8;
          $this->Cell(135, $height, 'Total Items: ' . $totalItems, 0, 0);
          $this->SetFillColor(255, 255, 255);
          $this->SetTextColor(0, 0, 0);
          $this->SetFont('Arial', 'B', 10);
          $this->Cell(30, $height, 'Subtotal:', 'LT', 0, 'R', true);
          $this->Cell(25, $height, '$' . number_format($subtotal, 2), 'TR', 1, 'R', true);
          $this->Cell(135, $height, '', 0, 0, '');
          $this->Cell(30, $height, 'Taxes (13%):', 'L', 0, 'R', true);
          $this->Cell(25, $height, '$' . number_format($taxes, 2), 'R', 1, 'R', true);
          $this->Cell(135, $height, '', 0, 0, '');
          $this->Cell(30, $height, 'Total:', 'LB', 0, 'R', true);
          $this->Cell(25, $height, '$' . number_format($totalAmount, 2), 'BR', 0, 'R', true);
     }

     function horizontalLine()
     {
          $this->SetFillColor(44, 62, 80);
          $this->Cell(190, 1, '', 0, 1, 0, true);
          $this->SetFillColor(255, 255, 255);
     }
}

if (!empty($_GET["order_id"])) {
     $orderId = (int) $_GET['order_id'];
     $pdf = new PDF();
     $pdf->SetAutoPageBreak(true, 20);
     $pdf->AddPage();

     $db = new Database();
     $connection = $db->getConnection();

     $order = new Order($connection);

     $orderDetails = $order->getOrderDetails($orderId);
     if (empty($orderDetails)) {
          http_response_code(404);
          exit('Order not found.');
     }

     $customerName = $orderDetails[0]['customer'];
     $customerEmail = $orderDetails[0]['customer_email'];
     $orderDate = $orderDetails[0]['order_date'];
     $customerAddress = $orderDetails[0]['shipping_address'];;
     $mobileNumber = $orderDetails[0]['contact_number'];;
     $zipCode = $orderDetails[0]['zip_code'];;

     $pdf->CustomerInfo($customerName, $customerEmail, $orderDate, $customerAddress, $mobileNumber, $zipCode);

     $pdf->Ln(8);

     $header = ['Product Name', 'Quantity', 'Unit Price', 'Total'];
     $pdf->ProductTable($header, $orderDetails);

     $subtotal = array_reduce($orderDetails, function ($carry, $item) {
          return $carry + ($item['product_quantity'] * $item['product_price']);
     }, 0);

     $taxes = $subtotal * 0.13;
     $totalAmount = $subtotal + $taxes;

     $totalItems = count($orderDetails);

     $pdf->OrderSummary($subtotal, $taxes, $totalAmount, $totalItems);

     $pdf->Output('F', "pdf/order_details_$orderId.pdf");

     header ("Location: thankYou.php?order_id=" . $orderId . "");
} else {
     $errors[] = "Order ID is not valid";
}
