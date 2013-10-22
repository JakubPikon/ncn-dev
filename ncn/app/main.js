angular.module('ncn', ['ngAnimate']).controller('NcnCtrl', ['$scope',
    function ($scope) {
		
		$scope.initNcn = function() {
			$scope.asideList = {};
			for(var i = 1; i < 10; i++) {
				$scope.asideList[i] = {
						'description' : 'list item ' + i + ' some description',
						'href' : 'descItem' + i,
						'id' : i
				};
			}
		};
		$scope.chooseMenu = function(id) {
			//$scope.showAside = true;
			$scope.showContent = true;
		};
		console.log(window.location);
		if(window.location.pathname === '/') {
			
		}
	}
]).directive('canvas', function() {
    return {
        restrict: 'A',
        link: function(scope) {
			var canvas = document.getElementById('canvas');
		
			if(canvas.getContext) {

				var ctx = canvas.getContext('2d'),
					img = new Image();   // Create new img element

				img.addEventListener("load", function() {
					// ctx.drawImage(img, 0, 0, 180, 200);
				}, false);
				img.src = '/img1.jpg';

			

				drawHex = function(elem, startX, startY) {
					
					console.log('drawing at from x: ' + startY + ' from y: ' + startX);
					elem.restore();
					ctx.beginPath();	
					elem.globalCompositeOperation = 'destination-over';
					elem.moveTo(startX, startY + 50);
					elem.lineTo(startX + 87, startY);
					elem.lineTo(startX + 175, startY + 50);
					elem.lineTo(startX + 175, startY + 150); 
					elem.lineTo(startX + 87, startY + 200);
					elem.lineTo(startX , startY + 150);
					elem.lineTo(startX + 0, startY + 50);
					elem.setFillColor('blue');
					elem.fill();

					elem.globalCompositeOperation = 'source-atop';
					elem.setFillColor('orange');
					elem.fillRect(startX, startY, 50, 50);
					elem.save();
				};

				var startX = 0, startY = 0, rowPosition = [], bHex = [];
				
				bHex[0] = [2];
				bHex[1] = [1,2,3,4];
				bHex[2] = [1,2,3];
				bHex[3] = [1,2,3,4];
				bHex[4] = [2];

				for (var i = 0; i < bHex.length; i++) {

					startY = (i * 150);
					startY = (i * 150);

					for (var j = 0; j < bHex[i].length; j++) {
						
						if( i === 0 || i%2 === 0) {
							startX = ((bHex[i][j] - 1) * 175) + 88;
						} else {
							startX = (bHex[i][j] - 1) * 175;
						}

						drawHex(ctx, startX, startY);
					}
				}
			}
        }
    };
});