<?php
    header("Content-type: application/vnd-ms-excel");
    header("Content-Disposition: attachment; filename=IT_MAN-SUPPORT-".$date.".xls");
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>RFM</title>
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="create-by" content="Reynaldi">
        <meta name="create-date" content="15/05/2019">
        <link href="<?php echo base_url('favicon.ico') ?>" rel="shortcut icon">
    </head>
    <body>
        <table width="100%" border="1">
            <tr>
                <th>NO RFM</th>
                <th>PIC</th>
                <th>REQUEST BY</th>
                <th>PROBLEM TYPE</th>
                <th>REQUEST TYPE</th>
                <th>SUBJECT</th>
                <th>DETAIL</th>
                <th>STATUS</th>
                <th>DATE</th>
            <?php
                foreach($row as $r):
                    echo "<tr>";
                    echo "<td>".$r->no_rfm."</td>";
                    echo "<td>".$r->pic."</td>";
                    echo "<td>".$r->request_by."</td>";
                    echo "<td>".$r->problem_type."</td>";
                    echo "<td>".$r->request_type."</td>";
                    echo "<td>".$r->subject."</td>";
                    echo "<td>".$r->detail."</td>";
                    echo "<td>".$r->status."</td>";
                    echo "<td>".$r->date."</td>";
                    echo "</tr>";
                endforeach;
            ?>
        </table>
    
    </body>
</html>