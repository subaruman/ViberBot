<?php

$auth_token = "4b5e762590e7ddb8-30a31dbdae9b3a73-6e442410ff4620ee";
$send_name = "covid19-ul";
$is_log = true;


function log_in($data)
{
    global $is_log;
    if ($is_log) {
        file_put_contents("tmp_in.txt", $data . "\n", FILE_APPEND);
    }
}

function log_out($data)
{
    global $is_log;
    if ($is_log) {
        file_put_contents("tmp_out.txt", $data . "\n", FILE_APPEND);
    }
}

function log_iosUsers($data)
{
    global $is_log;
    if ($is_log) {
        file_put_contents("ios.txt", $data . "\n", FILE_APPEND);
    }
}

function isIOS($id, $auth_token)
{
    $data["id"] = $id;
    $data["auth_token"] = $auth_token;
    $request_data = json_encode($data);

    $ch = curl_init("https://chatapi.viber.com/pa/get_user_details");
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_ENCODING, "");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $request_data);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);
    $arResponse = json_decode($response, true);

//    log_out(PHP_EOL);
//    log_out($response);

    $ios = substr($arResponse["user"]["primary_device_os"], 0 , 2);
    if ($ios === "iOS") {
        log_iosUsers($arResponse["user"]["id"]);
    }

}

function sendReq($data)
{
    $data['keyboard'] = [
        "Type" => "keyboard",
        "DefaultHeight" => true,
        "Buttons" => [
            [
                "ActionType" => "reply",
                "ActionBody" => "Где получить полную и достоверную информацию о коронавирусе?",
                "Columns" => 6,
                "Rows" => 1,
                "Text" => "🦠 Где получить полную и достоверную информацию о коронавирусе❓",
                "Image" => "https://www.ulstu.club/viber-bot/img/Information.jpg",
                "TextSize" => "regular"
            ],
            [
                "ActionType" => "reply",
                "ActionBody" => "Общая информация",
                "Columns" => 3,
                "Rows" => 1,
                "Text" => "Общая информация 💡",
                "Image" => "https://www.ulstu.club/viber-bot/img/General.jpg",
                "TextSize" => "regular"
            ],
            [
                "ActionType" => "reply",
                "ActionBody" => "Профилактика",
                "Columns" => 3,
                "Rows" => 1,
                "Text" => "Профилактика 💊",
                "Image" => "https://www.ulstu.club/viber-bot/img/profilactika.jpg",
                "TextSize" => "regular"
            ],
            [
                "ActionType" => "reply",
                "ActionBody" => "Карантин дома",
                "Columns" => 3,
                "Rows" => 1,
                "Text" => "Карантин дома 🛏",
                "Image" => "https://www.ulstu.club/viber-bot/img/home.jpg",
                "TextSize" => "regular"
            ],
            [
                "ActionType" => "reply",
                "ActionBody" => "Карантин в больнице",
                "Columns" => 3,
                "Rows" => 1,
                "Text" => "Карантин в больнице 💉",
                "Image" => "https://www.ulstu.club/viber-bot/img/hospital.jpg",
                "TextSize" => "regular"
            ],
            [
                "ActionType" => "reply",
                "ActionBody" => "Диагностика и лечение",
                "Columns" => 3,
                "Rows" => 1,
                "Text" => "Диагностика и лечение 📊",
                "Image" => "https://www.ulstu.club/viber-bot/img/Diagnostic.jpg",
                "TextSize" => "regular"
            ],
            [
                "ActionType" => "reply",
                "ActionBody" => "Самоизоляция",
                "Columns" => 3,
                "Rows" => 1,
                "Text" => "Самоизоляция 🏠",
                "Image" => "https://www.ulstu.club/viber-bot/img/izolation.jpg",
                "TextSize" => "regular"
            ],
            [
                "ActionType" => "reply",
                "ActionBody" => "Вызвать волонтера",
                "Columns" => 6,
                "Rows" => 1,
                "Text" => "Вызвать волонтера 📱",
                "Image" => "https://www.ulstu.club/viber-bot/img/volonter.jpg",
                "TextSize" => "regular"
            ],
            [
                "ActionType" => "open-url",
			    "ActionBody" => "https://instagram.com/morozov_si/",
                "Columns" => 6,
                "Rows" => 1,
                "Text" => "Видеообращение от губернатора",
                "Image" => "https://www.ulstu.club/viber-bot/img/gubernator.jpg",
                "TextSize" => "regular"
            ],
        ]
    ];

//    isIOS($data["id"], $data["auth_token"]);

    $request_data = json_encode($data);
//    log_in($request_data);


//    https://chatapi.viber.com/pa/get_user_details
//    https://chatapi.viber.com/pa/send_message
    $ch = curl_init("https://chatapi.viber.com/pa/send_message");
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_ENCODING, "");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $request_data);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);

    $err = curl_error($ch);
    curl_close($ch);
    if ($err) {
        return $err;
    } else {
        return $response;
    }
}

function sendMsg($sender_id, $text, $type, $tracking_data = Null, $arr_asoc = Null)
{
    global $auth_token, $send_name;

    $welcome = "Добрый день! 
\nПриветствуем Вас в чат-боте оперативного штаба по противодействию распространения коронавируса в Ульяновской области. Мы поможем вам найти ответы на самые популярные вопросы по теме. 
\nЧтобы начать, нажмите на требуемую кнопку.
";

    $answer1 = "— Оперативный штаб Ульяновской области по ситуации с коронавирусом в социальных сетях и мессенджерах:
    \nВКонтакте: https://vk.com/covid2019ul 
    \nViber: https://vk.cc/as156u
    \nTelegram: @covid19_73
    \nЯндекс-чат: https://vk.cc/as152Y
    \nОдноклассники: https://ok.ru/covid2019ul
    \nInstagram: https://instagram.com/covid2019ul/
\n— Сайт Губернатора и Правительства Ульяновской области:  https://ulgov.ru/page/index/permlink/id/19432/
\n— Роспотребнадзор:
\nРоссии: https://rospotrebnadzor.ru/
\nУльяновской области: http://73.rospotrebnadzor.ru/
\n— Минздрав России https://covid19.rosminzdrav.ru/
\n— Сайт СТОПКОРОНАВИРУС.РФ
\n📞 Телефоны горячих линий:\n
— 112 — единый мониторинговый центр (с 8:00 до 22:00)\n
— 8 (800) 100 00 04 — Роспотребнадзор\n
— 8 (800) 100 86 03 — Министерство здравоохранения Ульяновской области\n
— 8 (800) 350 46 46 — единый социальный телефон\n
— 8 (8422) 41 41 45 — горячая линия для бизнеса в МФЦБ\n
— 8 (8422) 44 46 61 — консультации по возврату денег за отмененные путевки\n
🎓 Телефоны горячих линий Министерства образования:\n
— 8 (8422) 21 42 58, 8 (8422) 21 42 39 — методическая помощь педагогам при реализации дистанционного обучения (по будням)\n
— 8 (927) 814 54 02 — оказание психологической помощи детям и их родителям (по будням с 09:00 до 17:00)\n
— 8 (8422) 37 01 67 — информационно-справочная линия министерства";

    $answer2 = "ℹ Общая информация
\n🦠 Что такое коронавирус?
\nЭто один из видов острой респираторной вирусной инфекции.
\n🔍 Какие симптомы у коронавируса?
\n❗ Симптомы во многом схожи со многими респираторными заболеваниями, часто имитируют обычную простуду, могут походить на грипп:
\n— Повышенная температура
\n— Чихание
\n— Кашель
\n— Затрудненное дыхание
\nЕсли вы обнаружили схожие симптомы, оставайтесь дома и вызывайте врача
\n⏳ В течение какого времени могут проявиться симптомы?
\nВ течение 14 дней после контакта с инфекционным больным.
\n\n✈ Как передается коронавирус?
\nВоздушно-капельным путем: выделение вируса происходит при кашле, чихании, разговоре. Контактно-бытовым путем: через предметы обихода.
\n\n🐈 Может ли домашнее животное заразить коронавирусом? 
\nНет, коронавирусы животных для человека не заразны.
\n\n🍎 Передается ли коронавирус через продукты? 
\nНет, срок жизни коронавируса за пределами живого организма не превышает 6-10 часов.
\n\n💧 Передается ли коронавирус через питьевую воду?
\nНет, но в целях профилактики возможных инфекций лучше использовать бутилированную воду.
\n\n📫 Несут ли товары или продукты из Китая особенные риски? 
\nНет, так как вирус не выживает вне живого организма.
\n\n🧓🏻 Кто находится в группе риска? 
\nНаибольший риск коронавирус представляет для пожилых людей в возрасте 65 лет и старше. Им следует соблюдать повышенные меры предосторожности: исключить контакты с больными и людьми, приехавшими из стран с неблагополучной эпидемиологической обстановкой, избегать посещения общественных мест. В случае появления респираторных симптомов сразу же обратиться к врачу. Однако, новый вирус остается опасным для всех, независимо от возраста, поэтому вышеперечисленные меры стоит предпринимать каждому.
\n\n⛑ Какие осложнения могут быть после коронавирусной инфекции?
\nНовая коронавирусная инфекция относится к острым респираторным вирусным инфекциям (ОРВИ) и осложнения могут быть такие же, как и у других ОРВИ — пневмония, бронхит, синусит и другие.
\n\n🏫 Какие меры введены в школах?
\nШколы переведены на дистанционное обучение.
\n\n⚖ Какие обязательства возлагаются на работодателя? 
\nРаботодатель обязан проводить всем сотрудникам термометрию и не допускать на работу лиц с признаками респираторной инфекции.
\nДля консультации можете воспользоваться номерами, указанными выше.";

    $answer3 = "ℹ Профилактика
\n💉 Где сделать вакцинацию от коронавируса?
\nНад вакциной работают российские и зарубежные ученые.
\n\n🧪 Где сдать анализ на коронавирус? 
\nСамостоятельная сдача анализов на коронавирус не предусмотрена. Обследованию подлежат:
\n— Граждане, прибывшие из других стран 
\n— Люди с признаками ОРВИ, прибывшие из стран, где зафиксированы случаи заболевания
\n— Лица, контактировавшие с заболевшим коронавирусной инфекцией
\nЛаборатории частных медицинских организаций не проводят исследования на выявление новой коронавирусной инфекции.
\n\n👩‍🚀 Что делать, если я узнал, что был в контакте с больным или людьми, с которыми он общался?
\nНеобходимо сообщить свои данные на горячую линию и перейти на режим самоизоляции — домашний карантин.
    ";

    $answer4 = "ℹ Карантин дома
\n🏖 Где размещаются граждане, которые прибыли из других стран, но не имеют признаков ОРВИ?
\nТем, кто прибыл из других стран необходимо перейти в режим самоизоляции на 14 дней, то есть не покидать жилище, не посещать работу, учебу, не приглашать к себе гостей. Получить консультацию по выдаче больничных листов можно, позвонив на горячую линию Министерства здравоохранения.
\nБольничные листы выдают независимо от самочувствия. Если приехали несколько членов семьи, то больничные листы оформят на всех по одной заявке.
\nНа карантине необходимо ежедневно сообщать о самочувствии и температуре тела в поликлинику по месту жительства. Если появились симптомы, сразу вызвать врача!
\n\n👨‍👩‍👧 Должны ли члены семьи, не посещавшие другие страны, тоже отправиться на карантин?
\nНет, если у прибывшего из заграницы не подтвердился коронавирус, но при возможности лучше воздержаться от контактов с родственниками на период карантина.
\n\n📆 Сколько длится карантин? 
\n14 дней с момента пересечения границы или с даты контакта с заболевшим новой коронавирусной инфекцией.
\n\n⛔ Что нельзя делать во время карантина? 
\nПокидать место проживания, посещать учебу и работу.
\nРиск инфицирования членов семьи снижается, если соблюдать основные гигиенические требования —использовать маску, индивидуальную посуду, часто мыть руки и пользоваться кожными антисептиками, регулярно проветривать помещения.
\n\n🌡 Сколько времени будут продолжаться ежедневные посещения врачами?
\nЧисло визитов врача определяется в каждом случае индивидуально в течение всего периода карантина.
\n\n💃 Что будет, если не соблюдать режим самоизоляции?
\nПравительство РФ внесло коронавирусную инфекцию в перечень заболеваний, представляющих опасность для окружающих, поэтому нарушение законодательства влечет ответственность, в том числе уголовную — вплоть до лишения свободы на срок до 5 лет.
\nПри нарушении режима предусмотрено размещение гражданина в обсерваторе.
\n\n👤 Если человек живет один, можно ли в режиме самоизоляции выносить на улицу мусор, принимать посылки от курьера, сходить в аптеку и за продуктами? 
\nРекомендуется пользоваться службой доставки, помощью друзей и знакомых. В случае крайней необходимости (выбросить мусор, погулять с собакой) возможно выходить на улицу в малолюдное время, обязательно в медицинской маске.
";
    $answer5 = "ℹ Карантин в больнице
\n🛏 Почему пациентов не изолируют друг от друга, а размещают в одной палате? 
\nЛюди с подозрением на коронавирусную инфекцию с одинаковыми сроками пересечения границы либо контакта с заболевшим лежат в маломестных палатах. При первом положительном результате анализа на коронавирусную инфекцию пациента незамедлительно изолируют.
\n\n👥 Кто подлежит госпитализации вместе с заболевшим?
\nРешение о госпитализации принимает проводящий осмотр врач в зависимости от тяжести состояния и близости контактов с заболевшим.
\n\n🧼 Как организована дезинфекция помещений в больницах, куда отвозят пациентов с подозрением на коронавирус?
\nДезинфекция проводится не менее 2 раз в сутки во всех помещениях больницы с применением специальных средств и физических методов обеззараживания воздуха и поверхностей, например, бактерицидные лампы и обеззараживатели воздуха.
\n\n👩‍⚕️Почему врачи покидают больницу, хотя сами контактировали с пациентами?
\nВо время работы персонал использует средства индивидуальной защиты: маски, респираторы, перчатки, медицинские шапочки. В конце каждой смены медперсонал сдает экипировку для утилизации и проходит полную санитарную обработку.
\n\n🙅‍♀️Могут ли родственники посещать пациента, который находится в режиме изоляции в больнице?
\nВ период, пока пациент находится в изоляции, родственники посещать его не могут. Эти меры введены для предотвращения распространения заболевания. Однако все, кто находятся на лечении в стационаре, всегда могут воспользоваться мобильным телефоном для связи с родными.
\n\n🆓 Сколько стоит нахождение в стационаре?
\nМедпомощь оказывают бесплатно.
\n\n🍏 Могут ли родственники приносить еду и другие необходимые вещи в стационар?
\nРодственники могут передавать пациентам продукты питания и личные вещи, однако существует ряд ограничений, которые можно уточнить, позвонив в справочную больницы.
\n\n🍲 Как организовано питание пациентов, находящихся в стационаре? 
\nДля пациентов стационаров с подозрением на новую коронавирусную инфекцию питание организовано в соответствии с санитарными нормами и правилами.
";

    $answer6 = "ℹ Диагностика и лечение
\n📊 Как и сколько времени идет диагностика? 
\nДостаточно одного теста, но при наличии симптомов заболевания его проводят не менее 3 раз.
\n\n🧪 Какие анализы берут для диагностики? 
\nДиагностика коронавирусной инфекции осуществляется молекулярно-генетическими методами — ПЦР (полимеразная цепная реакция). Для исследования берется мазок из носа и ротоглотки, а также проводятся другие анализы по назначению врача.
\n\n💊 Как лечат людей, пока они ждут результаты анализов?
\nЗависит от симптомов, главное — не заниматься самолечением.
\n\n🚫 Какие меры нужно соблюдать после выписки из стационара?
\nПосле выписки необходимо соблюдать такие же меры профилактики вирусных инфекций, как и здоровым людям, — избегать массовых скоплений людей, мыть руки, проветривать помещения.
    ";

    $answer7 = "🏠Чем развлечь себя на самоизоляции? 
    \n🎭 Держите каталог всех онлайн сервисов ульяновскдома.рф
    \nНа сайте собраны как ульяновские, так федеральные и международные сервисы, которые позволят вам все делать не выходя из дома: учиться, работать, покупать продукты, получать волонтерскую помощь, заказывать вывоз мусора и многое другое.
    ";

    $answer8 = "Волонтерский центр принимает заявки от всех телефонов «горячих линий», которые работают в регионе в  связи с пандемией коронавируса, в том числе 112 и «горячей линии» «Единой России» 8(8422) 58-00-08
\n👆 Присоединиться к команде и стать волонтером можно по этим номерам телефонов. 
\nВступить в ряды активистов также можно, подав заявку на сайте добровольцыроссии.рф или в Инстаграме instagram.com/happy_region73/
\n📦 Доставка на дом товаров первой необходимости почтальонами.
\nСвыше тысячи работников могут доставить нуждающимся людям продукты, которые реализуются в отделении почты, по предварительной заявке, оставленной жителями на телефон горячей линии: 8(927)828-76-62, или в службе 112
";
    $data = ["id" => $sender_id];
    $data['auth_token'] = $auth_token;
    $data['receiver'] = $sender_id;
    if ($text !== null) {
        if ($text === 'conversation_started') {
            $data['text'] = $welcome;
        } else if ($text === 'subscribed') {
            $data['text'] = $welcome;
        } else if ($text === 'https://instagram.com/morozov_si/') {
            $data['text'] = null;
        } else if ($text === "Где получить полную и достоверную информацию о коронавирусе?") {
            $data['text'] = $answer1;
        } else if ($text === "Общая информация") {
            $data['text'] = $answer2;
        } else if ($text === "Профилактика") {
            $data['text'] = $answer3;
        } else if ($text === "Карантин дома") {
            $data['text'] = $answer4;
        } else if ($text === "Карантин в больнице") {
            $data['text'] = $answer5;
        } else if ($text === "Диагностика и лечение") {
            $data['text'] = $answer6;
        } else if ($text === "Самоизоляция") {
            $data['text'] = $answer7;
        } else if ($text === "Вызвать волонтера") {
            $data['text'] = $answer8;
        } else {
            $data['text'] = "Выберите кнопку из предложенных вариантов.";
        }

    }
    $data['type'] = $type;
    //$data['min_api_version'] = $input['sender']['api_version'];
    $data['sender']['name'] = $send_name;
    //$data['sender']['avatar'] = $input['sender']['avatar'];
    if ($tracking_data != Null) {
        $data['tracking_data'] = $tracking_data;
    }
    if ($arr_asoc != Null) {
        foreach ($arr_asoc as $key => $val) {
            $data[$key] = $val;
        }
    }

    return sendReq($data);
}



$request = file_get_contents("php://input");
$input = json_decode($request, true);

//log_out($request);

$type = $input['message']['type']; //type of message received (text/picture)
$text = $input['message']['text']; //actual message the user has sent
$sender_id = $input['sender']['id']; //unique viber id of user who sent the message
$sender_name = $input['sender']['name']; //name of the user who sent the message
$subscribe_id = $input['user']['id'];

//log_in($sender_id);

if ($input['event'] == 'webhook') {
    $webhook_response['status'] = 0;
    $webhook_response['status_message'] = "ok";
    $webhook_response['event_types'] = 'delivered';
    echo json_encode($webhook_response);
    die;
} else {
    if ($input['event'] === "subscribed") {
        sendMsg($subscribe_id, "subscribed", "text");
    }
    if ($input['event'] === "conversation_started") {
        sendMsg($subscribe_id, "conversation_started", "text");
    }
    if ($input['event'] === "message") {
        sendMsg($sender_id, $text, "text");
    }
}

?>