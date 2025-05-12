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
            <h3 class="text-center fw-bold">سياسية الشحن والاسترجاع</h3>
            <ol>
                <li class="fw-bold">سياسة عامة</li>
                <p class="fw-bold"></p>نظرًا لطبيعة المنتجات الغذائية، فإن جميع الطلبات تعتبر نهائية بعد البدء في تحضيرها، ولا يُسمح بإرجاعها أو استرداد قيمتها، إلا في الحالات التالية:</p>
                <ul>
                    <li>استلام طلب مختلف عن المطلوب.</li>
                    <li>وجود ضرر واضح أو فساد في المنتج.</li>
                    <li>تأخير مفرط في التوصيل (أكثر من 60 دقيقة من وقت الطلب بدون إشعار مسبق).</li>
                </ul>
                <p class="fw-bold">آلية التقديم:</p>
                <ul>
                    <li>يجب تقديم الشكوى خلال ساعة واحدة من استلام الطلب.</li>
                    <li>يُرجى التواصل مع خدمة العملاء عبر [البريد الإلكتروني / رقم الهاتف].</li>
                    <li>يجب إرفاق صورة للمنتج (إن أمكن) مع رقم الطلب.</li>
                </ul>
                <p class="fw-bold">إجراءات الاسترداد:</p>
                <ul>
                    <li>في حال الموافقة، يتم إصدار قسيمة شرائية أو استرداد المبلغ إلى بطاقة فيزا  خلال 7-10 أيام عمل.</li>
                    <li>لا يتم استرداد رسوم التوصيل أو الخدمات الإضافية.</li>
                </ul>
                <p class="fw-bold">الاستثناءات:</p>
                <ul>
                    <li>لا تُقبل الشكاوى بسبب اختلاف الذوق أو بعد استهلاك الطعام.</li>
                    <li>لا يُمكن استرجاع الطلبات المخصصة أو المعدّلة حسب رغبة العميل.</li>
                </ul>
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