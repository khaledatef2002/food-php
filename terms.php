<?php include "temps/settings.php"; ?>
<?php include "includes/functions.php"; ?>
<!DOCTYPE html>

<html lang="<?php echo $site_setting['lang']; ?>" dir="<?php echo $site_setting['dir']; ?>">

<head>
  <?php include "temps/head.php"; ?>
  <title><?php echo $site_setting['site-title']; ?> - Order Page</title>
</head>

<body>
  <?php include "temps/header.php"; ?>
  <div class="sections order-page col-lg-8 col-12 mx-auto">
    <div class="row col-lg-10 col-md-10 col-sm-10 col-12 mx-auto">
      <div class="item">
        <div style="border-radius: 7px;padding: 10px 45px;font-size:20px;">
            <h3 class="text-center fw-bold">الشروط والاحكام</h3>
            <p>
            يرجى قراءة هذه الشروط والأحكام بعناية قبل استخدام موقعنا. باستخدامك للموقع فإنك توافق على الالتزام بهذه الشروط.
            </p>
            <ol>
                <li class="fw-bold">قبول الشروط:</li>
                <p>عند دخولك او استخدام لهذا الموقع فإنك توافق على الالتزام بجميع الشروط الواردة في هذه الصفحة بالإضافة إلى أي سياسات أو إرشادات أخرى منشورة في الموقع</p>
                <li class="fw-bold">طرق الدفع:</li>
                <p>نقبل الدفع الإلكتروني عبر بطاقات فيزا وغيرها من البطاقات البنكية المعتمدة من خلال بوابة دفع آمنة وموثوقة.</p>
                <li class="fw-bold">تفويض المعاملة:</li>
                <p>عند إتمام عملية الشراء، يؤكد العميل أنه المالك الشرعي للبطاقة، وأنه يوافق على خصم كامل المبلغ المستحق.</p>
                <li class="fw-bold">أمان المعلومات:</li>
                <p>جميع بيانات الدفع مشفرة بالكامل باستخدام تقنيات الحماية المتقدمة، وتخضع لنظام التحقق (OTP) لضمان الأمان التام.</p>
                <li class="fw-bold">تأكيد الطلب:</li>
                <p>فور إتمام عملية الدفع، يتلقى العميل إشعارًا عبر البريد الإلكتروني أو رسالة نصية تحتوي على تفاصيل الطلب والفاتورة.</p>
                <li class="fw-bold">إلغاء الطلب:</li>
                <p>يمكن إلغاء الطلب خلال 5 دقائق فقط من تأكيده، بشرط عدم بدء التحضير أو التوصيل. بعد ذلك، يُعتبر الطلب نهائيًا وغير قابل للإلغاء.</p>
            </ol>
        </div>
      </div>
    </div>
  </div>

  <div class="footer" style="    display: flex;
    justify-content: center;
    align-items: center;
    font-weight: bold;">
    <?php include 'temps/footer.php'; ?>
  </div>
  <?php include 'temps/jslibs.php'; ?>
</body>

</html>