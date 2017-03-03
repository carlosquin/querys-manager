# Querys-manager
Class written in php language, for the handling of database queries using the mysqli and object-oriented library.

#Reason:
This library helps you write more readable code by interpreting faster the requests that are made within your system and make modifications to the flight.

# Use:
<p>Initialize class:</p><br>
<code>$sql = new Querys();</code><br><br>
<b>Select:</b><br>
<code>$sql->select(array(table'=>'table name here','field'=>'fields name to select here (comma separated)'));</code><br>
<b>Example Select:</b><br>
<code>$sql->select(array(table'=>'customers','field'=>'name, id, addres'));</code><br>
<hr>

<b>Update:</b><br>
<code>$sql->update(
					array(
						'table' => 'table name here',
						'field' => 'Field and value',
						'where' => 'Field and value'
						)
					);</code><br>
          
<b>Example update:</b><br>
<code>$sql->update(
					array(
						'table' => 'table
						'field' => 'name=jhon,lastname=doe,addres=local',
						'where' => 'id=10'
						)
					);</code>
<br>
#Coming further changes
