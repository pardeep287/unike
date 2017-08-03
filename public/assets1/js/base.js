var $jq=jQuery.noConflict();
$jq(function () {


	$jq('.subnavbar1').find ('li').each (function (i) {

		var mod = i % 3;

		if (mod === 2) {
			$jq(this).addClass ('subnavbar-open-right');
		}

	});



});