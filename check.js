function required()
{
var empt = document.forms["form"]["ICO"].value;
    if (empt == "")
    {
        alert("Please input a Value");
        return false;
    }
    return true;
}