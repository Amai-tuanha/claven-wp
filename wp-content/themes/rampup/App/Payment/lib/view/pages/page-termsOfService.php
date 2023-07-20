<?php

// use Carbon\Carbon;
// do_action('page_termsOfService__send_form');
// ---------- GETの値からユーザーIDを取得し、ユーザー情報を読み込む ----------

include(get_theme_file_path() . "/App/Payment/model/include-termsOfService.php");

?>
<?php get_header(); ?>
<link rel="stylesheet" href="<?= rampup_css_path('page-mypage.css') ?>">
<link rel="stylesheet" href="<?= rampup_css_path('page-cardPayment.css') ?>">
<link rel="stylesheet" href="<?= rampup_css_path('page-termsOfService.css') ?>">
<link rel="stylesheet" href="<?= rampup_css_path('form-common.css') ?>">

<?php include(get_theme_file_path() . "/App/Common/lib/view/components/common-header.php") ?>
<?php
if (!$_GET['user_id']) {
  $user_id = get_current_user_id();
} else {
  $user_id = $_GET['user_id'];
}
$class_user = new Rampup_User($user_id);
?>

<div class="inner">
  <ol class="step">
    <li class="is-current">利用規約</li>
    <li class="settlement">決済</li>
    <?php if (
      $class_user->user_paymentMethod === 'card' &&
      $class_user->user_status === 'application'
    ) { ?>
      <li>完了</li>
    <?php } ?>
  </ol>
  <script src="https://yubinbango.github.io/yubinbango/yubinbango.js" charset="UTF-8"></script>
  <div id="reserveForm" class="formTemplate">

    <div class="-tac">
      <h2 class="formTemplate__heading">利用規約</h2>
    </div>

    <div class="formTemplate__pageContents">
      <div class="terms-of-service__inner">
        <h2 class="terms-of-service__title -noIndent">CLANEインターン型実践講座<br>
          コンサルティングサービス利用規約</h2>
          <div class="formTemplate__formContentWrapper">
        <p class="terms-of-service__text -noIndent">この規約（以下「本規約」といいます。）は、株式会社CLANE（以下「当社」といいます。）が提供するCLANEインターン型実践講座コンサルティングサービス（以下「本サービス」といいます。）の利用に関する条件を、本サービスを利用するお客様（以下「お客様」といいます。）と当社との間で定めるものです。</p>
          <h4 class="terms-of-service__subtitle">第１条 コンサルティングの内容</h4>
          <p class="terms-of-service__text -noIndent">当社はお客様に対し、下記の内容のコンサルティングを実施するものとします。</p>
          
          <p class="terms-of-service__text">１ WEBサイトのデザインおよびコーディング</p>
          <p class="terms-of-service__text">２ WEBサイトの受託制作に関する進行管理</p>
          
          <h4 class="terms-of-service__subtitle">第２条 コンサルティングの方法</h4>
          <p class="terms-of-service__text -noIndent">当社はお客様に対し、下記の方法でコンサルティングを実施するものとします。</p>
          <p class="terms-of-service__text">１ 会員サイトの提供</p>
          <p class="terms-of-service__text -largeIndent">（１）当社はお客様に会員サイトのアカウントを発行し、前項のコンサルティング内容に関するノウハウを体系化した一連のコンテンツおよび演習を提供するものとします。</p>
          <p class="terms-of-service__text -largeIndent">（２）当社は会員サイトの仕様および提供する一連のコンテンツを随時、追加・変更・削除できるものとし、当社は追加・変更があった場合にはお客様に対してSlackにて通知するものとします。</p>
          <p class="terms-of-service__text">２ 質問・相談・フィードバック</p>
          <p class="terms-of-service__text -largeIndent">（１）当社は、お客様が当社の提供する基本演習および実践演習に従って制作した制作物に対する添削・フィードバックを行うものとします。</p>
          <p class="terms-of-service__text -largeIndent">（２）お客様は、前項のコンサルティング内容に関する質問や相談をすることができるものとします。</p>
          <p class="terms-of-service__text -largeIndent">（３）質問・相談・フィードバックは、当社の事業所またはSlackを用いて、当社の営業時間内に行うものとします。</p>
          <p class="terms-of-service__text">３ 勉強会の開催</p>
          <p class="terms-of-service__text -noIndent">勉強会の内容および日程等に関しては、その都度Slackまたは会員サイトにてお客様に通知するものとします。</p>
          <p class="terms-of-service__text">４ プロジェクトへの参画</p>
          <p class="terms-of-service__text -largeIndent">（１）当社は、お客様が提供する演習を全て終了しており、直近2ヶ月間で開催された勉強会の出席率が60%以上であることを条件として、お客様が従事するプロジェクトに参画させることができるものとします。</p>
          <p class="terms-of-service__text -largeIndent">（２）当社は、お客様に対して2週間前に告知することで前項の条件を変更できるものとします。</p>
          <p class="terms-of-service__text -largeIndent">（３）参画するプロジェクトの内容および条件については、その都度個別に定めるものとします。</p>
          <h4 class="terms-of-service__subtitle">第３条 費用について</h4>
          <p class="terms-of-service__text">１ お客様は当社に対して、ssss円（税抜き）をコンサルティング料として支払うものとします。支払い回数および方法は、当社所定の内容に従います。</p>
          <h4 class="terms-of-service__subtitle">第４条 有効期間</h4>
          <p class="terms-of-service__text">１ 本契約の有効期間は、本契約書の締結から12ヶ月間とします。</p>
          <p class="terms-of-service__text">２ 契約期間満了までにお客様が継続の意思表示を行った場合、12ヶ月以内に当社が提供する研修を終了することを条件として、本契約を更新することができるものとします。更新後の契約期間は12ヶ月間とし、以後も同様とします。</p>
          <h4 class="terms-of-service__subtitle">第５条 所有権</h4>
          <p class="terms-of-service__text">１ お客様は、コンサルティングに付随して提供される一切の資料、データおよび、コンサルティングを受けてお客様が制作した制作物についての所有権が当社に帰属することを認めるものとします。</p>
          <p class="terms-of-service__text">２ 本条の規定は、本契約終了後も存続するものとします。</p>
          <h4 class="terms-of-service__subtitle">第６条 著作権</h4>
          <p class="terms-of-service__text">１ お客様は、当社に対し、お客様の著作物の当社又は当社が許諾した第三者による一切の利用（著作権法第27条及び第28条に定める権利を含む）を許諾するものとします。</p>
          <p class="terms-of-service__text">２ お客様は、当社に対し、お客様の著作物に関する著作者人格権を行使しないものとします。</p>
          <p class="terms-of-service__text">３ 本条の規定は、本契約終了後も存続するものとします。</p>
          <h4 class="terms-of-service__subtitle">第７条 契約の解除</h4>
          <p class="terms-of-service__text -noIndent">当社は、お客様が次の各号のいずれか一つに該当した時は、催告その他の手続きを要しないで、直ちに本契約を解除することができるものとします。</p>
          <p class="terms-of-service__text">１ 本契約に違反し、お客様が当社に対し14日以上の相当な期間を定めて、当該違反行為の是正を催告したにも関わらず、その期間内に当該是正催告に従わなかったとき。</p>
          <p class="terms-of-service__text">２ 正当な理由なく、支払いが滞ったとき。</p>
          <p class="terms-of-service__text">３ 差押、仮差押、保全差押、仮処分の申し立て又は滞納処分を受けたとき</p>
          <p class="terms-of-service__text">４ 破産、民事再生、特別清算その他裁判上の倒産処理手続の申し立てを受けた時又は自らこれらの申し立てをしたとき。</p>
          <p class="terms-of-service__text">５ 公租公課の滞納処分を受けたとき。</p>
          <p class="terms-of-service__text">６ 死亡した時又は後見開始、保佐開始もしくは補助開始の審判を受けたとき。</p>
          <p class="terms-of-service__text">７ 重大な契約違反または背信行為があったとき。</p>
          <p class="terms-of-service__text">８ 本契約の継続を困難とする事由が発生したとき。</p>
          <p class="terms-of-service__text">９ その他、前号に類する重大な違反があったとき。</p>
          <h4 class="terms-of-service__subtitle">第８条 反社会的勢力の排除</h4>
          <p class="terms-of-service__text">１ お客様及び当社は、それぞれ相手方に対し、次の各号に捧げる事項を確約するものとします。</p>
          <p class="terms-of-service__text -largeIndent">（１）自らが、暴力団、暴力団員、暴力団員でなくなった時から5年を経過していない者、暴力団準構成員、暴力団関係企業、総会屋等、社会運動等標ぼうゴロ又は特殊知能暴力集団等、その他これらに準ずる者若しくはその構成員 (以下、総称して「反社会的勢力」という。)ではないこと、及び反社会的勢力と社会的に非難されるべき関係を有していないこと</p>
          <p class="terms-of-service__text -largeIndent">（２）自らの役員(取締役、執行役、執行役員、業務請負を執行する社員、監査役又はこれらに準ずる者をいう。)が反社会的勢力ではないこと、及び反社会的勢力と社会的に非難されるべき関係を有していないこと</p>
          <p class="terms-of-service__text -largeIndent">（３）反社会的勢力に自己の名義を利用させ、本契約を締結するものでないこと</p>
          <p class="terms-of-service__text -largeIndent">（４）自らまたは第三者を利用して、本契約に関して次の行為をしないこと</p>
          <p class="terms-of-service__text -ml"> １相手方に対する脅迫的な言動又は暴力を用いる行為</p>
          <p class="terms-of-service__text -ml"> ２偽計又は威力を用いて相手方の業務を妨害し、又は信用を毀損する行為</p>
          <p class="terms-of-service__text -ml"> ３法的な責任を超えた不当な要求行為</p>
          <p class="terms-of-service__text -ml"> ４その他1から3までの行為に準ずる行為</p>
          <p class="terms-of-service__text">２ お客様及び当社は、相手方が次の各号のいずれかに該当した場合には本契約を何らの催告を要しないで、直ちに解除することができるものとします。</p>
          <p class="terms-of-service__text -largeIndent">（１）前項第1号又は第2号の確約に反する申告ないし表明をしたことが判明した場合</p>
          <p class="terms-of-service__text -largeIndent">（２）前項第3号の確約に反し、本契約等を締結したことが判明した場合</p>
          <p class="terms-of-service__text -largeIndent">（３）前項第4号の確約に反する行為をした場合</p>
          <p class="terms-of-service__text">３ 前項の規定により、本契約が解除された場合には、解除された者は、その相手方に対し、相手方の被った損害を賠償するものとします。</p>
          <p class="terms-of-service__text">４ 第２項の規定により、本契約が解除された場合には、解除された者は、解除により生じた損害について、その相手方に対し一切の請求を行わないものとします。</p>
          <h4 class="terms-of-service__subtitle">第９条 権利の譲渡等の禁止</h4>
          <p class="terms-of-service__text -noIndent">お客様は、当社の書面による承諾がない限り、本契約上の地位を第三者に移転し、本契約に基づく権利の全部若しくは一部を第三者に譲渡し、若しくは第三者の担保に供し、又は、本契約に基づく義務の全部若しくは一部を第三者に引き受けさせてはならないものとします。</p>
          <h4 class="terms-of-service__subtitle">第１０条 秘密保持</h4>
          <p class="terms-of-service__text">１ 本契約において、「秘密情報」とは、本契約の存在並びに内容、及び本件業務遂行のためお客様又は当社が相手方から開示を受けた技術上または営業上その他業務上の情報をいいます。但し、次の各号のいずれか一つに該当する情報は秘密情報に該当しないものとします。</p>
          <p class="terms-of-service__text -largeIndent">（１） 相手方から開示を受ける前にすでに保有していた情報</p>
          <p class="terms-of-service__text -largeIndent">（２） 秘密保持義務を負うことなく第三者から正当に入手した情報</p>
          <p class="terms-of-service__text -largeIndent">（３） 相手方から提供を受けた情報によらず、独自に開発した情報</p>
          <p class="terms-of-service__text -largeIndent">（４） 本契約に違反することなく、かつ、受領の前後を問わず公知となった情報</p>
          <p class="terms-of-service__text">２ お客様及び当社は、秘密情報を第三者に開示または漏洩してはならないものとします。但し、事前に相手方からの書面による承諾を受けることにより、第三者へ開示することができるものとします。なお、法令の定めに基づきまたは権限ある官公署から開示の要求があった場合は、当該法令の定めに基づく開示先に対し開示することができるものとします。</p>
          <p class="terms-of-service__text">３ お客様及び当社は、当該秘密情報を秘密として管理する義務を負い、そのために必要な措置を講ずるものとします。</p>
          <p class="terms-of-service__text">４ お客様及び当社は、秘密情報について、本契約の目的の範囲でのみ使用するものとし、本契約の目的の範囲を超える複製、改変が必要なときは、事前に相手方から書面による承諾を受けるものとします。</p>
          <p class="terms-of-service__text">５ 本条の規定は、本契約終了後も存続するものとします。</p>
          <h4 class="terms-of-service__subtitle">第１１条 免責</h4>
          <p class="terms-of-service__text -noIndent">本契約に基づくコンサルティングは、必ずしもお客様に対し、ある一定の利益や成果、有益な機会の提供を保証するものではなく、また、本契約に関連して当社その他第三者に損害・トラブルが生じた場合でも当社は故意または重過失がある場合を除き、当社はその責任を負わないものとします。</p>
          <h4 class="terms-of-service__subtitle">第１２条 協議解決</h4>
          <p class="terms-of-service__text -noIndent">本契約に定めのない事項及び本契約の内容の解釈に疑義が生じた事項については、両当事者間で誠実に協議の上、これを解決するものとします。</p>
          <h4 class="terms-of-service__subtitle">第１３条 準拠及び管轄裁判所</h4>
          <p class="terms-of-service__text">１ 本契約の準拠法は、日本法とします。</p>
          <p class="terms-of-service__text">２ 本契約に関する一切の紛争については、東京地方裁判所又は東京簡易裁判所を第一審の専属的合意管轄裁判所とします。</p>
        </div>
          
      </div>
      <?php //do_action('terms_of_service_contents') 
      ?>
    </div>
    <br><br><br>

    <?php if (!$class_user->user_termsOfService) {    ?>
      <form method="POST" action="<?php echo get_permalink(); ?>" class="h-adr">
        <input type="hidden" name="termsOfServiceTrigger">

        <input type="hidden" name="user_id" value="<?= $class_user->user_id ?>">
        <input type="hidden" name="user_email" value="<?= $class_user->user_email ?>">
        <div class="formTemplate__inputBox">
          <label class="formTemplate__inputLable">名前<span class="-error">*</span></label>
          <div class="formTemplate__inputItem -flex">
          <input class="formTemplate__input" name="user_displayName" type="text" placeholder="名前" value="<?= $class_user->user_displayName; ?>" required>
            <!-- <input class="formTemplate__input" style="margin-right:1rem" type="text" name="user_lastName" placeholder="姓" value="<?= $class_user->user_lastName; ?>" required>
            <input class="formTemplate__input" type="text" name="user_firstName" placeholder="名" value="<?= $class_user->user_firstName; ?>" required> -->
          </div>
        </div>

        <div class="formTemplate__inputBox">
          <span class="p-country-name" style="display:none;">Japan</span>
          <label class="formTemplate__inputLable">郵便番号<span class="-error">*</span></label>
          <div class="formTemplate__inputItem -flex">
            <span class="-zip">〒</span><input type="text" class="p-postal-code formTemplate__input -width20" name="user_postalcode_first" size="3" maxlength="3" value="<?= $class_user->user_postalcode_first; ?>" required>
            <input type="text" class="p-postal-code formTemplate__input -width25" name="user_postalcode_last" size="4" maxlength="4" value="<?= $class_user->user_postalcode_last; ?>" required><br>
          </div>
        </div>
        <div class="formTemplate__inputBox">
          <label class="formTemplate__inputLable">都道府県<span class="-error">*</span></label>
          <div class="formTemplate__inputItem -flex">
            <input type="text" class="p-region formTemplate__input" name="user_region" value="<?= $class_user->user_region; ?>" required />
          </div>
        </div>
        <div class="formTemplate__inputBox">
          <label class="formTemplate__inputLable">市区町村<span class="-error">*</span></label>
          <div class="formTemplate__inputItem -flex">
            <input type="text" class="p-locality p-street-address formTemplate__input" name="user_locality" value="<?= $class_user->user_locality; ?>" required />
          </div>
        </div>
        <div class="formTemplate__inputBox">
          <label class="formTemplate__inputLable">丁目・番地・号<span class="-error">*</span></label>
          <div class="formTemplate__inputItem -flex">
            <input type="text" class="p-extended-address formTemplate__input" name="user_chome" value="<?= $class_user->user_chome; ?>" />
          </div>
        </div>
        <div class="formTemplate__inputBox">
          <label class="formTemplate__inputLable">建物名・部屋番号</label>
          <div class="formTemplate__inputItem -flex">
            <input type="text" class="p-extended-address formTemplate__input" name="user_building" value="<?= $class_user->user_building; ?>" />
          </div>
        </div>
        <div class="formTemplate__inputBox">
          <label class="formTemplate__inputLable">生年月日<span class="-error">*</span></label>
          <div class="formTemplate__inputItem -flex">
            <select id="year" class="formTemplate__selectItem -width20" name="user_dob_year" required>
              <option value="" selected disabled>年</option>
            </select>
            <select id="month" class="formTemplate__selectItem -width20" name="user_dob_month" required>
              <option value="" selected disabled>月</option>
            </select>
            <select id="date" class="formTemplate__selectItem -width20" name="user_dob_date" required>
              <option value="" selected disabled>日</option>
            </select>
          </div>
        </div>
        <div class="formTemplate__inputBox">
          <label class="formTemplate__inputLable">メールアドレス<span class="-error">*</span></label>
          <div class="formTemplate__inputItem -flex">
            <input class="formTemplate__input" name="user_email" type="email" value="<?= $class_user->user_email ?>" required>
          </div>
        </div>
        <div class="formTemplate__inputBox">
          <label class="formTemplate__inputLable">電話番号<span class="-error">*</span></label>
          <div class="formTemplate__inputItem -flex">
            <input class="formTemplate__input" name="user_tel" type="tel" value="<?= $class_user->user_tel ?>" required>
          </div>
        </div>

        <div class="-tac">

          <input type="checkbox" id="termsCheck" name="user_termsOfService" value="on" required>
          <label for="termsCheck" class="termscheck">利用規約に同意する</label>
          <br><br>

          <div class="formTemplate__cta -mt2">
            <!-- <button class="cta__button -maxwidth__300"
                  type="submit"
                  onclick="disableButton(event)">送信</button> -->
            <button class="cta__button -maxwidth__300" type="submit">送信</button>
          </div>
        </div>
      </form>
    <?php } elseif ($class_user->user_termsOfService === 'on') {
    ?>

      <div id="termsOfService_formSent" class="formTemplate__wrap -flex">


        <p class="formTemplate__whitebox -tac"><i class="fas fa-check"></i> 利用規約に同意済みです</p>

        <?php if ($class_user->user_paymentMethod === 'card') { ?>
          <div class="-tac -m0a">
            <a class="cta__button" href="<?= $class_user->user_card_payment_link() ?>">
              クレジットカード決済に進む <i class="fas fa-arrow-right"></i>
            </a>
          </div>

        <?php } ?>
      </div>
    <?php } ?>
  </div>
</div>
<script>
  (function() {
    // ライブラリ
    /**
     * 任意の年が閏年であるかをチェックする
     * @param {number} チェックしたい西暦年号
     * @return {boolean} 閏年であるかを示す真偽値
     */
    const isLeapYear = year => (year % 4 === 0) && (year % 100 !== 0) || (year % 400 === 0);

    /**
     * 任意の年の2月の日数を数える
     * @param {number} チェックしたい西暦年号
     * @return {number} その年の2月の日数
     */
    const countDatesOfFeb = year => isLeapYear(year) ? 29 : 28;

    /**
     * セレクトボックスの中にオプションを生成する
     * @param {string} セレクトボックスのDOMのid属性値
     * @param {number} オプションを生成する最初の数値
     * @param {number} オプションを生成する最後の数値
     * @param {number} 現在の日付にマッチする数値
     */
    const createOption = (id, startNum, endNum, current) => {
      const selectDom = document.getElementById(id);
      let optionDom = '';
      for (let i = startNum; i <= endNum; i++) {
        if (i === current) {
          option = '<option value="' + i + '">' + i + '</option>';
        } else {
          option = '<option value="' + i + '">' + i + '</option>';
        }
        optionDom += option;
      }
      selectDom.insertAdjacentHTML('beforeend', optionDom);
    }

    // DOM
    const yearBox = document.getElementById('year');
    const monthBox = document.getElementById('month');
    const dateBox = document.getElementById('date');

    // 日付データ
    const today = new Date();
    const thisYear = today.getFullYear();
    const thisMonth = today.getMonth() + 1;
    const thisDate = today.getDate();

    let datesOfYear = [31, countDatesOfFeb(thisYear), 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];

    // イベント
    // monthBox.addEventListener('change', (e) => {
    //   dateBox.innerHTML = '';
    //   const selectedMonth = e.target.value;
    //   createOption('date', 1, datesOfYear[selectedMonth - 1], 1);
    // });

    // yearBox.addEventListener('change', e => {
    //   monthBox.innerHTML = '';
    //   dateBox.innerHTML = '';
    //   const updatedYear = e.target.value;
    //   datesOfYear = [31, countDatesOfFeb(updatedYear), 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];

    //   createOption('month', 1, 12, 1);
    //   createOption('date', 1, datesOfYear[0], 1);
    // });

    // ロード時
    createOption('year', 1950, thisYear, thisYear);
    createOption('month', 1, 12, thisMonth);
    createOption('date', 1, datesOfYear[thisMonth - 1], thisDate);
  })();
</script>
<?php include get_theme_file_path() . "/App/Mypage/lib/view/components/component-mypage-footer.php" ?>
<?php include get_theme_file_path() . "/footer-default.php" ?>
<?php get_footer();
