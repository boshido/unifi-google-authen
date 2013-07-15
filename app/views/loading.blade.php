<!DOCTYPE html>
<html>
	<head>
		<title>Loading User infomation</title>
		<meta charset="utf-8"/>
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
		<script>
			if (navigator.userAgent.match(/IEMobile\/10\.0/)) {
			  var msViewportStyle = document.createElement("style");
			  msViewportStyle.appendChild(
				document.createTextNode(
				  "@-ms-viewport{width:auto!important}"
				)
			  );
			  document.getElementsByTagName("head")[0].
				appendChild(msViewportStyle);
			}
		</script>
		<style>
			#loading{
				margin-top:100px;
			}
		</style>
		<link href="/css/overide.css" rel="stylesheet" media="screen">
	</head>
	<body style="text-align:center;">
		
		<canvas id="loading" width="200" height="200" ></canvas>

		<script src="/js/jquery-2.0.3.js" type="text/javascript"></script>
		<script>
			
			$(document).ready(function(){
				
			});
			
			window.requestAnimationFrame = window.requestAnimationFrame ||
					window.webkitRequestAnimationFrame ||
					window.mozRequestAnimationFrame;

			var Graph = function (canvas, color) {
				color = color || 'rgb(110, 200, 255)';

				this.canvas_ = canvas;
				this.ctx_ = canvas.getContext('2d');

				this.width_ = canvas.width;
				this.height_ = canvas.height;

				this.color_ = color;
				this.target_ = 0;
				this.current_ = 0;
				this.render_();
			};

			Graph.prototype.set = function (percent) {
				this.target_ = percent > 100 ? 100 : percent;
				this.render_();
			};

			Graph.prototype.render_ = function () {
				if (this.current_ > this.target_) {
					return;
				}

				var ctx = this.ctx_;
				var w = this.width_;
				var h = this.height_;
				var r = Math.floor(w / 2);
				var l = Math.ceil(5 * w / 200) + 3;

				var arc = 2 * this.current_ / 100;
				arc = arc > 2 ? 2 : arc;
				arc = (this.current_ === 0) ? 0 : arc;
				this.current_ += 1;

				ctx.shadowOffsetX = 0;
				ctx.shadowOffsetY    = 0;
				ctx.shadowBlur = 0;
				ctx.shadowColor = 'transparent';

				ctx.globalCompositeOperation = 'destination-out';
				ctx.fillStyle = 'black';
				ctx.fillRect(0, 0, w, h);
				
				ctx.globalCompositeOperation = 'source-over';
				ctx.fillStyle = 'rgba(0, 0, 0, 0.5)';
				ctx.beginPath();
				ctx.arc(r, r, r, 0, 2 * Math.PI);
				ctx.fill();

				ctx.globalCompositeOperation = 'destination-out';
				ctx.fillStyle = 'black';
				ctx.beginPath();
				ctx.arc(r, r, r + 1, 1.5 * Math.PI, (1.5 + arc + 0.009) * Math.PI);
				ctx.lineTo(r - 1, r);
				ctx.lineTo(r - 1, 0);
				ctx.fill();

				ctx.globalCompositeOperation = 'source-over';
				ctx.fillStyle = this.color_;
				ctx.beginPath();
				ctx.arc(r, r, r, 1.5 * Math.PI, (1.5 + arc + 0.005) * Math.PI);
				ctx.lineTo(r, r);
				ctx.lineTo(r, 0);
				ctx.fill();

				ctx.globalCompositeOperation = 'destination-out';
				ctx.fillStyle = 'black';
				ctx.beginPath();
				ctx.arc(r, r, r - l, 0, 2 * Math.PI);
				ctx.fill();

				ctx.globalCompositeOperation = 'source-over';
				ctx.fillStyle = 'rgba(0, 0, 0, 0.05)';
				ctx.beginPath();
				ctx.arc(r, r, r - 2 * l, 0, 2 * Math.PI);
				ctx.fill();

				ctx.globalCompositeOperation = 'destination-out';
				ctx.strokeStyle = 'black';
				ctx.beginPath();
				ctx.arc(r, r, r - 2 * l - (r > 50 ? 3 : 2), 0, 2 * Math.PI);
				ctx.stroke();
				ctx.globalCompositeOperation = 'source-over';

				ctx.fillStyle = 'rgba(0, 0, 0, 0.5)';
				var size = 10 + Math.ceil(30 * w / 200);
				ctx.font = size + 'px Melbourne-Bold';
				ctx.textAlign = 'center';
				ctx.textBaseline = 'middle';
				ctx.fillText(this.target_ , r, r);

				requestAnimationFrame(this.render_.bind(this));
			};


			var loaded1 = 0;

			var g1 = new Graph(document.getElementById('loading'), '#F47063');


			g1.set(loaded1);
			setTimeout(function add() {
				loaded1 += Math.floor(Math.random() * 3) + 1;
				g1.set(loaded1);
				if (loaded1 < 100 ) {
					setTimeout(add,{{$second}}0);
				}
				else{
					$('#loading').fadeOut(function(){
						window.location.href ="{{$url}}";
					});
				}
			}, {{$second}}0);		
		</script>
	</body>
</html>
