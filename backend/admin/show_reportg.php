
<?php include('header.php') ;?>
<head>
<link rel="stylesheet" type="text/css"  media="print">
    <title>รายงานรายรับ</title>
    <style>
   
       
        .topp{
            margin-top: 40%;
        }
        .back{
            background-color: #e2e2e2;
        }
        p,li{font-family: 'Prompt', sans-serif;}
        hr{
            border-top: 1px solid #c0c0c0;
            width: 90%;
        }
        #rl{
            padding-left: 5%;
            padding-right: 5%;
        }
        .btn-info{
           background-color: #4a4aff;
       }
    span,button,th,td,h5,h3{font-family: 'Prompt', sans-serif;}
    th{
        text-align: center;
        color: #000;
        background-color: #dadada;
    }
    td{
        color: #000;
    }
    /* .sol{
        border: 2px solid black;
        border-collapse: collapse;
         } */
    
    .col1{
        background-color: #fffaf7;
    }
    .col2{
        background-color: #fff1ec;
    }
    .col3{
        background-color: #ffe7dd;
    }
    .col4{
        background-color: #ff9999;
    }
    @media print {
    .hpp{
        display:none;
      
    }
    #dr{
      display: none;
     
    /* size: A4 landscape; */
    }
      }
    </style>
   
</head>
<script>
          function printDiv(divName) {
          var printContents = document.getElementById(divName).innerHTML;
          var originalContents = document.body.innerHTML;
          document.body.innerHTML = printContents;
          window.print();
          document.body.innerHTML = originalContents;
      }
    </script>

<body>

   
        </aside>

            <div class="container-fluid">
                <div class="row" style="margin-left: 25%;" >
                    <div class="col-10">
                        <div >
                            <div class="card-body">
                               
                                <form action="" method="POST">
                                    <div class="form-body">
                                        <h3 style="padding-left:30% ; color:#000">รายงานยอดรวมของผู้เช่า</h3><br><br>
                                        <div class="row input-daterange">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                    <span>วันที่</span>
                                                        <input type="date" class="form-control" name="datestart" placeholder="วันเริ่มต้น">
                                                    </div>
                                                </div>
                                                    <div>
                                                        <br><span class="btn btn-dark" onClick="myFunction()"> ถึง</span> 
                                                    </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                    <span>วันที่</span>
                                                        <input type="date" class="form-control" name="dateend" placeholder="วันสิ้นสุด">
                                                    </div>
                                                </div>
                                            
                                            <div class="form-actions">
                                                <div class="text-right">
                                                    <br><button type="submit" class="btn btn-info">แสดงรายงาน</button>
                                                    
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    
                </div>
            
                <!-- ============================================================== -->

                <!-- Start Page Content -->
                <!-- ============================================================== -->
                <!-- basic table -->

                <?php
       
                    function DateThai($strDate)
                    {
                        $strYear = date("Y",strtotime($strDate))+543;
                        $strMonth= date("n",strtotime($strDate));
                        $strDay= date("j",strtotime($strDate));
                        
                        
                        $strMonthCut = Array("","ม.ค.","ก.พ.","มี.ค.","เม.ย.","พ.ค.","มิ.ย.","ก.ค.","ส.ค.","ก.ย.","ต.ค.","พ.ย.","ธ.ค.");
                        $strMonthThai=$strMonthCut[$strMonth];
                        return "$strDay $strMonthThai $strYear";
                    }
                    ?>  
        <?php
        if (isset($_POST['datestart'])&&isset($_POST['dateend'])) {
            // $date=date_create_from_format("j/m/Y", $_POST['datestart']);
            // $datestart=date_format($_POST['datestart'], "Y-m-d");
            $datestart=$_POST['datestart'];
            $dateend=$_POST['dateend'];


            // $date=date_create_from_format("j/m/Y", $_POST['dateend']);
            // $dateend=date_format($date, "Y-m-d");
        ?>                
                <div class="row" >
                
                    <div class="col-12" id="rl">       
                        <div class="card" >
                        
                            <div class="card-body">
                            <!-- <button id="br" onclick="printDiv('printableArea')"class="btn btn-success"> พิมพ์ PDF <i class="fa fa-print"></i></button> -->
                        
                                <h5 class="card-title" style="text-align: center;"><?php echo $_POST['datestart']." ถึง ".$_POST['dateend']."<br>";?></h5>
                              
                                <div class="table-responsive"  id="printableArea">

                       
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>ลำดับ</th>
                                            <th>ผู้เช่า</th>
                                            <th>ชื่อแผง</th>
                                            <th>จำนวนเงิน</th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr style="text-align: center;">
                                           <?php
                                           
                                           $sql2="SELECT
                                           tb_rent.renter_id,
                                           (tb_rent.rent_rental+ tb_rent.rent_pledge) as summ ,
                                          
                                           tb_renter.renter_fullname,
                                           tb_market.numberbox,
                                           tb_type.name_type,
                                           DATE(tb_rent.rent_dtime)AS rent_dtime
                                           FROM
                                           tb_rent
                                           INNER JOIN tb_market ON tb_rent.market_id = tb_market.market_id
                                           INNER JOIN tb_renter ON tb_rent.renter_id = tb_renter.renter_id
                                           INNER JOIN tb_type ON tb_market.id_type = tb_type.id_type
                                            WHERE
                                           DATE(rent_dtime) BETWEEN '$datestart' AND '$dateend'
                                          ";
                                        //  echo $sql2;
                                            $a=1;
                                            $bill_datetimeold="";
                                            $sum_people_qty=0;
                                            $sum_bill_price=0;
                                            $sumpricr=0;
                                            $sumtotal=0;

                                                            $result=$cls_conn->select_base($sql2);
                                                            while ($row2 = mysqli_fetch_array($result)) {
                                                            // $date=date_create($row2["bill_datetime"]);
                                                            // $bill_datetime=date_format($date,"d/m/Y");
                                                            
                                                            // if ($bill_datetimeold!=$bill_datetime) {
                                                            //     echo"<td style='text-align: center;'>";
                                                            //     echo DateThai($row2["bill_datetime"]);
                                                            //     echo"</td>";
                                                            // } else {
                                                            //     echo "<td></td>";
                                                            // } 
                                                        
                                                            ?>

                                            <td style="text-align: center;"><?=$a?></td>
                                            <td style="text-align: center;"><?=$row2['renter_fullname']?></td>
                                            <td style="text-align: center;"><?=$row2['numberbox']?></td>
                                            <!-- <td style="text-align: center;"><?=$row2['type_name']?></td> -->
                                            <!-- <td style="text-align: center;"><?=$row2['numberbox']?></td> 
                                            <td style="text-align: center;"><?=$row2['renter_fullname']?></td> -->
                                            <td style="text-align: center;"><?=number_format($row2['summ'],2)?></td>
                                            
                                          
                                        </tr>
                                        <?php
                                        $sum_people_qty+=$row2['people_qty'];
                                        $sum_bill_price+=$row2['pay_money'];
                                        $bill_datetimeold=$bill_datetime;
                                        $a++;
                                        $sumtotal+=$row2['summ'];
                                                        }
                                        ?>
                                        <tr class="col1" style="text-align: center;">
                                            <td colspan="2">รวมจำนวนรายการ</td>
                                            <td><?=$a-1?></td>
                                            <td>รายการ</td>
                                        </tr>
                                        <!-- <tr class="col2" style="text-align: center;">
                                            <td colspan="5">รวมจำนวนผู้เข้าใช้บริการ</td>
                                            <td><?=$sum_people_qty?></td>
                                            <td>คน</td>
                                        </tr> -->
                                        <!-- <tr class="col3" style="text-align: center;">
                                            <td colspan="2">เงินรวม</td>
                                            <td><?=number_format($sum_bill_price, 2)?></td>
                                            <td>บาท</td>
                                        </tr> -->
                                        <?php
                                        $sum_bill_price_total+=$sum_bill_price;
                                        
                                        }
                                        ?>
                                    </tbody>
                                    <tr class="col4" style="text-align: center;">
                                            <td colspan="2">เงินรวมทั้งสิ้น</td>
                                            <td><?=number_format($sumtotal,2)?></td>
                                            <td>บาท</td>
                                    </tr>
                                </table>
                               
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>



<?php

include('footer.php');
?>
