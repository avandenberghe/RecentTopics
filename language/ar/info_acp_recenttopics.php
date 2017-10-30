<?php
/**
 *
 * @package Recent Topics Extension
 * Arabic translation by Bassel Taha Alhitary (www.alhitary.net)
 *
 * @copyright (c) 2015 PayBas
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 * Based on the original NV Recent Topics by Joas Schilling (nickvergessen)
 */

if (!defined('IN_PHPBB'))
{
	exit;
}
if (empty($lang) || !is_array($lang))
{
	$lang = array();
}

$lang = array_merge(
	$lang, array(
	//forum acp
	'RECENT_TOPICS_LIST'            => 'تفعيل أحدث المواضيع ',
	'RECENT_TOPICS_LIST_EXPLAIN'    => 'السماح بعرض مواضيع هذا المنتدى في قائمة "أحدث المواضيع".',

	//acp title
	'RECENT_TOPICS'                 => 'أحدث المواضيع',
	'RT_CONFIG'                     => 'الإعدادات',
	'RECENT_TOPICS_EXPLAIN'         => 'من هنا تستطيع التحكم بالإعدادات الخاصة بالإضافة : أحدث المواضيع.<br /><br />تستطيع تحديد المنتديات التي تريد عرضها أو استثنائها من العرض في أحدث المواضيع بالذهاب إلى اعدادات المنتدى في لوحة التحكم الرئيسية.<br />أيضاً تأكد من صلاحيات الأعضاء , حيث تستطيع السماح لهم بالتعديل على بعض الخيارات من لوحة التحكم الخاصة بهم.',

	//global settings
	'RT_GLOBAL_SETTINGS'            => 'الإعدادات العامة',
	'RT_DISPLAY_INDEX'              => 'العرض في الصفحة الرئيسية ',
	'RT_NUMBER'                     => 'عدد المواضيع ',
	'RT_NUMBER_EXP'                 => 'عدد المواضيع التي تريد عرضها.',
	'RT_PAGE_NUMBER'                => 'عرض جميع الصفحات ',
	'RT_PAGE_NUMBER_EXP'            => 'انقر على المربع إذا رغبت في تجاهل الخيار التالي ( الحد الأقصى لعدد الصفحات ).',
	'RT_PAGE_NUMBERMAX'             => 'الحد الأقصى لعدد الصفحات ',
	'RT_PAGE_NUMBERMAX_EXP'         => 'تحديد الحد الأقصى لعدد الصفحات التي تريد عرضها ( من 1 إلى 999 ). هذا الخيار لا يعمل في حالة تحديد الخيار السابق ( عرض جميع الصفحات ).',
	'RT_MIN_TOPIC_LEVEL'            => 'نوع المواضيع  ',
	'RT_MIN_TOPIC_LEVEL_EXP'        => 'تحديد نوع المواضيع التي تريد عرضها. سيتم العرض من الأقل إلى الأكثر.',
	'RT_ANTI_TOPICS'                => 'المواضيع المُستبعدة ',
	'RT_ANTI_TOPICS_EXP'            => 'اكتب علامة الفاصلة ", " بين أرقام المواضيع التي تريد عدم ظهورها في "أحدث المواضيع" ( مثال : 7,9 )',
	'RT_PARENTS'                    => 'إظهار المنتدى الأب ',
	'RT_PARENTS_EXP'                => 'إظهار إسم "المنتدى الأب" في التفاصيل المذكورة تحت أسم الموضوع.',

	//User Overridable settings. these apply for anon users and can be overridden by UCP
	'RT_OVERRIDABLE'                => 'الإعدادات الرئيسية',
	'RT_LOCATION'                   => 'مكان العرض ',
	'RT_LOCATION_EXP'               => 'حدد المكان لظهور أحدث المواضيع.',
	'RT_TOP'                        => 'الأعلى',
	'RT_BOTTOM'                     => 'الأسفل',
	'RT_SIDE'                       => 'الجانب',
	'RT_SORT_START_TIME'            => 'الترتيب حسب وقت إضافة الموضوع ',
	'RT_SORT_START_TIME_EXP'        => 'اختيارك "نعم" يعني ترتيب أحدث المواضيع بحسب وقت إضافة الموضوع , بدلاً من الترتيب بحسب وقت آخر مشاركة.',
	'RT_UNREAD_ONLY'                => 'عرض المواضيع الغير مقروءة فقط ',
	'RT_UNREAD_ONLY_EXP'            => 'تفعيل هذا الخيار يعني اظهار المواضيع الغير مقروءة فقط ( بغض النظر لو هذه المواضيع حديثة أو قديمة ). هذا الخيار يستخدم نفس اعدادات الطريقة العادية ( استبعاد المنتديات/المواضيع..الخ ).<br />ملاحظة : هذا الخيار يظهر فقط للأعضاء المسجلين دخولهم للمنتدى. الزائرين سيُشاهدون القائمة العادية.',
	'RT_RESET_DEFAULT'              => 'إعادة الضبط ',
	'RT_RESET_DEFAULT_EXP'          => 'إعادة ضبط الإعدادات الخاصة بالعضو إلى الإفتراضية.',

	//Enable for extensions
	'RT_NICKVERGESSEN_NEWSPAGE'     => 'يدعم الإضافة : صفحة الأخبار',
	'RT_VIEW_ON'                     => 'إظهار أحدث المواضيع في :',

	//Donation
	'RT_DONATE_URL'             => 'http://www.avathar.be/bbdkp/app.php/page/donate',
	'PAYPAL_IMAGE_URL'          => 'https://www.paypalobjects.com/webstatic/en_US/i/btn/png/silver-pill-paypal-26px.png',
	'PAYPAL_ALT'                => 'التبرع بواسطة PayPal',
	'RT_DONATE'					=> 'التبرع لدعم الإضافة',
	'RT_DONATE_SHORT'			=> 'تبرع لدعم الإضافة : أحدث المواضيع ',
	'RT_DONATE_EXPLAIN'			=> 'هذه الإضافة مجانية 100%. وهي أحد هواياتي التي استمتع بها والتي تستهلك وقتي ونقودي على تحديث هذه الإضافة بصورة مُستمرة. ارجوا التفكير بالتبرع لهذه الإضافة لو استمتعت بإستخدامها. وسأكون ممتناُ لك. بلا شروط أو قيود.',
	)
);
