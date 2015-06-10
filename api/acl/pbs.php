<?php
header ("Content-Type:text/xml");
echo '<?xml version="1.0" encoding="utf-8" ?>
<rms_input>
	<inp>
		<title>Category</title>
		<type>select</type>
		<name>category</name>
		<val>
			<v1>value of input 1</v1>
			<v2>value of input 2</v2>
			<v3>value of input 3</v3>
		</val>
	</inp>
	<inp>
		<title>Access Control</title>
		<type>select, multiple</type>
		<name>role</name>
		<val>
			<v1>value of input 1</v1>
			<v2>value of input 2</v2>
			<v3>value of input 3</v3>
			<v4>value of input 4</v4>
		</val>
	</inp>
	<inp>
		<title>Sample input</title>
		<type>text</type>
		<name>sample</name>
		<val>
			<v1></v1>
		</val>
	</inp>
</rms_input>';
?>