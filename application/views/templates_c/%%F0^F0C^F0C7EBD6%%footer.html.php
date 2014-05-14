<?php /* Smarty version 2.6.27, created on 2014-05-13 05:05:13
         compiled from ./common/footer.html */ ?>
<div id="footerWrapper">
	<div id="footer">
		<div class="footerWrap">
			<div class="column1">
				<div><a href="/"><img src="/img/pc/common/footer_logo.png" alt="Re:gurume" /></a></div>
				<ul class="snsNav inline"><li><a href="https://www.facebook.com/Regurume" target="_blank"><img src="/img/pc/common/icon_fb.png" alt="" /></a></li><li><a href="https://twitter.com/regurume" target="_blank"><img src="/img/pc/common/icon_tw.png" alt="" /></a></li></ul>
				<div class="boxDemand" id="contact_input">
					<form action="">
                        <div class="block02">
                            <textarea name="contact_text" id="contact_text" rows="5" placeholder="ご意見をお寄せください"></textarea>
                        </div>
                         <div class=" text-right">
                             <button type="button" id="btnContactSend" class="btn btnC01 btnContactSend">送信</button>
                         </div>
                        <input type="hidden" id="contact_user_id" name="contact_user_id" value="<?php echo $this->_tpl_vars['user_id']; ?>
" />
                        <input type="hidden" id="contact_user_name" name="contact_user_name" value="<?php echo $this->_tpl_vars['user_name']; ?>
" />
                    </form>
				</div>
				<div id="contact_done" style="display:none;">
				    ありがとうございます！！
				    <br>
				    <?php echo $this->_tpl_vars['user_name']; ?>
さんのご意見を送信しました！
				</div>
				<div id="mail_failure" style="display:none;">
				    <p>申し訳ございません。データを受信できませんでした。</p>
				    <p>
				        大変お手数ですが、Regurume公式アカウント
				        <a target="_blank" href="http://regurume.com">Re:gurume</a>
				            または info@regurume.com までご連絡いただけますと幸いです。
				    </p>
				</div>
				<p id="copy">&copy;2013 Re:gurume</p>
			</div>
			<div class="column2">
					<ul class="navFooter">
					<li><a href="/about/">Re:gurumeの楽しみ方</a></li>
					<li><a href="/ranking/input">ランキングを作る</a></li>
					<li><a href="/search/searchranking">ランキングを探す</a></li>
					<li><a href="/search/searchshop">お店を探す</a></li>
				</ul>
				<ul class="navFooter">
					<li><a href="/login/newsignup">無料会員登録</a></li>
					<li><a href="/login/">ログイン</a></li>
					<li><a href="/rules/">利用規約</a></li>
					<li><a href="/policy/">プライバシーポリシー</a></li>
					<li><a href="mailto:info@regrume.com">お問い合わせ</a></li>
					<li><a href="http://www.axas-japan.co.jp/" target="_blank">運営会社</a></li>
				</ul>
			</div>
		<div class="chara1"></div>
		</div>
	</div>
</div>