Your file is ready.<br>
Get it by <a href="{{ url('uploaded', $file)  }}"><b>this link.</b></a><br>
<br>
Some info<br>
<b>Name of file:</b> {{  $file->name }}<br>
<b>Description:</b> {{  $file->description }}<br>
<br>
Load another file you can <a href="{{ url('') }}">here</a>.