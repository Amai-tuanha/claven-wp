// // プッシュ通知の承諾画面出力
// (function () {
// 	if ("Notification" in window) {
// 		var permission = Notification.permission;

// 		if (permission === "denied" || permission === "granted") {
// 			return;
// 		}

// 		Notification
// 			.requestPermission()
// 			.then(function () {
// 				// var notification = new Notification("Hello, world!");
// 			});
// 	}
// })();

// /**
//  *
//  * @param {String} title
//  * @param {String} description
//  */
// function push_function(title, description) {
// 	Push.create(title,
// 		{
// 			body: description,
// 			icon: "https://calendartest.check-demo.site/wp-content/themes/rampup/img/clane_icon.png",
// 			timeout: 8000,
// 			onClick: function () {
// 				window.focus();
// 				this.close();
// 			}
// 		}
// 	);
// }
