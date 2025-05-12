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
            <h3 class="text-center fw-bold">سياسة الخصوصية</h3>
            <p>
                نحن نولي أهمية قصوى لحماية خصوصية مستخدمينا. تهدف هذه السياسة إلى توضيح كيفية جمع واستخدام وحماية المعلومات الشخصية التي تقدمها لنا عند استخدامك لخدماتنا.
            </p>
            <ol>
                <li class="fw-bold">المعلومات التي نجمعها</li>
                <div class="d-flex gap-2">
                    <p class="fw-bold">المعلومات الشخصية:</p> <p>مثل الاسم الكامل، رقم الهاتف، عنوان البريد الإلكتروني، وعنوان التوصيل.</p>
                </div>
                <li class="fw-bold">كيفية استخدام المعلومات</li>
                <ul>
                    <li>معالجة الطلبات وتوصيلها إلى العنوان المحدد.</li>
                    <li>تحسين جودة خدماتنا وتجربة المستخدم.</li>
                    <li>الامتثال للمتطلبات القانونية والتنظيمية.</li>
                </ul>
                <li class="fw-bold">حماية المعلومات</li>
                <p>نلتزم باتخاذ جميع الإجراءات الأمنية المناسبة لحماية معلوماتك الشخصية من الوصول غير المصرح به أو التعديل أو الكشف أو التدمير.</p>
                <li class="fw-bold">ملفات تعريف الارتباط (Cookies)</li>
                <p>نستخدم ملفات تعريف الارتباط لتحسين تجربة المستخدم، مثل تذكر إعداداتك وتفضيلاتك. يمكنك تعديل إعدادات المتصفح لرفض ملفات تعريف الارتباط، ولكن قد يؤثر ذلك على وظائف الموقع.</p>
                <li class="fw-bold">حقوق المستخدم</li>
                <ul>
                    <li>الوصول إلى معلوماتك الشخصية وتحديثها.</li>
                    <li>طلب حذف معلوماتك الشخصية.</li>
                </ul>
                <li class="fw-bold">التعديلات على سياسة الخصوصية</li>
                <p>قد نقوم بتحديث هذه السياسة من وقت لآخر. سيتم نشر أي تغييرات على هذه الصفحة، ونشجعك على مراجعتها بانتظام.</p>
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