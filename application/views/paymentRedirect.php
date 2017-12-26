<?php
if (isset($error)) {
    echo $error . "Please Try Again!";
} else {
    ?>
    Redirecting...
    <form method="post" name="redirect"
          action="https://test.ccavenue.com/transaction/transaction.do?command=initiateTransaction">
        <?php

        echo '<input type=hidden name=encRequest value="' . $data . '"">';
        echo '<input type=hidden name=Merchant_Id value="' . $merchant . '">';
        echo '<input type=hidden name=access_code value="' . $access_code . '">';
        ?>
    </form>
    <script language='javascript'>document.redirect.submit();</script>
    </body>
    </html>
    <?php
}
?>