// (function($, document, window) {

// 	tinymce.create( 'tinymce.plugins.insertHtml', {
// 	init : function( editor, url ) {
// 		// editor には現在開いてるエディタの情報が入る
// 		// url にはこのjsファイルまでのパスが入る
// 		// ボタン設定
// 		editor.addButton( 'button_insert_html', {
// 			title : insert_html_button_title,
// 			image : url + '/button_insert_html.png',
// 			onclick: function() {
// 							//モーダルウィンドウが出て来る処理
// 							editor.windowManager.open({
// 								title : insert_html_window_title,
// 								body: [{
// 								type: 'textbox',
// 								name: 'code',
// 								multiline: true,
// 								style: "width: 400px; max-width: 80vw; height: 150px;"
// 							}],
// 							onsubmit: function(e) {
// 								editor.insertContent( e.data.code );
// 							}
// 						});
// 					}
// 				});
// 			},
// 				createControl: function( n, cm ) {
// 					return null;
// 			}
// 		});
// 	// ボタン追加
// 	tinymce.PluginManager.add( 'custom_button_script', tinymce.plugins.insertHtml );

// }) (jQuery, document, window);