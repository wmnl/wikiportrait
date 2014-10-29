		<div class="footer">
			Wikiportret is een initiatief van Wikimedia Nederland
		</div>
		</div>

		<script src="<?php echo $basispad ?>/scripts/jQuery.SimpleSelect/jquery.simpleselect.min.js"></script>
		<script src="<?php echo $basispad ?>/scripts/jquery.autosize.min.js"></script>

		<script>
		$(".select").simpleselect({
			displayContainerInside: "container"
		});

		$(document).ready(function(){
			$('textarea').autosize();
		});

		function confirmDelete(id){
		    if (confirm("Weet je zeker dat je dit bericht wilt verwijderen?")) {
			location.href = "deletemessage.php?id=" + id;
		    }
		    else {
			return;
		    }
		}

		document.addEventListener("touchstart", function(){}, true);
		</script>
	</body>
</html>
