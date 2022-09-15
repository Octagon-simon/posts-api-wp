(function ($) {
	'use strict';

	/**
	 * All of the code for your admin-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */

	$(window).load(function () {
		//Rename first child of posts-api-wp menu
		document.querySelectorAll('.wp-first-item .wp-first-item').forEach(m => {
			if(m.innerText == "Posts API WP"){
				m.innerText = "Get Started";
				return;
			}
		});
		
		//remove admin notices from all plugin pages
		document.querySelectorAll('.wrap').forEach(w => {
			//check if wrap element has posts-api-wp as its id
			if (w.id.match(/posts-api-wp/i)) {
				//select all notice tabs and remove
				document.querySelectorAll('.notice').forEach(n => {
					n.remove();
				})
			}
		})
		//prevent the user from selecting -1 as post number
		$('#postsNum').on('change', function () {
			//set the number to 1
			this.value = (this.value < 5) ? 5 : this.value;
		})

		//generate random characters
		const randChars = function (length = 32) {
			let res = '';
			const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ123456789abcdefghijklmnopqrstuvwxyz';

			let i = 0;
			while (i < length) {
				res += characters.charAt(Math.floor(Math.random() * characters.length));
				i++;
			}
			return res;
		}
		//auto generate auth key
		$('#inp_authKey').val(randChars(32));
		//handle key regeneration
		$('#btn_gen_authKey').on('click', function () {
			$('#inp_authKey').val(randChars(32));
		})

		//handle author meta
		$('#chk_author_meta').on('change', function () {
			if (this.checked) {
				//show the select element
				$('#select_author_meta').show();
			} else {
				//hide the select element
				$('#select_author_meta').hide();
			}
		})

		//handle post date
		$('#chk_format_post_date').on('change', function () {
			if (this.checked) {
				//show the select element
				$('#section_post_date_format').show()
				//handle custom date format
				$('#select_post_date_format').on('change', function () {
					if (this.value == "custom") {
						//show the custom section
						$('#section_post_date_custom_format').show()
					} else {
						//hide the custom section
						$('#section_post_date_custom_format').hide()
					}
				})
			} else {
				//hide the select element
				$('#section_post_date_format').hide()
			}
		})
		
		//handle additional fields
		$('.chk-all-add-fields').on('change', function(){
			//if the checkbox is checked
			if(this.checked){
				document.querySelectorAll('.chk-add-fields').forEach(box => {
					box.checked = true;
				})
			}else{
				document.querySelectorAll('.chk-add-fields').forEach(box => {
					box.checked = false;
				})
			}
		})
	})

})(jQuery);
