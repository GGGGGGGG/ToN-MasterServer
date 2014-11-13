<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
	"http://www.w3.org/TR/html4/loose.dtd">
<html>
	<head>
		<title>S2 Master server test</title>
		<script src="jquery-1.3.2.min.js" type="text/javascript" charset="utf-8"></script>
		<script type="text/javascript">
			$(function() {
				$('#send').click(function() {
					$.post($('#action').val(), $('#body').val(), function(data) {
						$('#result').html(data);
					});
					return false;
				});
			});
		</script>
		<style type="text/css">
			input, textarea, select {
				width: 400px;
				margin: 5px;
			}
			
			textarea {
				height: 100px;
			}
		</style>
	</head>
	<body>
		<h1>Simulate action</h1>
		<form>
			<div>
				<select name="action" id="action">
					<option value="/irc_updater/irc_stats.php">Insert stats</option>
					<option value="/irc_updater/irc_requester.php">Comm vote</option>
				</select>
			</div>
			<div>
				<textarea name="body" id="body"></textarea>
			</div>
			<div>
				<input type="submit" value="Send request" id="send" />
			</div>
		</form>
		<h1>Result</h1>
		<div id="result">Nothing yet</div>
	</body>
</html>