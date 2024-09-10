const hoursBox = document.getElementById("hours");
const minutesBox = document.getElementById("minutes");

const dateBox = document.getElementById("date");
const dayBox = document.getElementById("day");

const events = document.getElementsByClassName("events")[0];

            
setInterval(() => {
    let xhr = new XMLHttpRequest();
    xhr.open("GET", "server.php?date");
    xhr.onreadystatechange = function()
    {
        if (xhr.readyState === 4 && xhr.status === 200)
        {
            let jsObj = JSON.parse(xhr.responseText);

            hoursBox.textContent = jsObj.hours.toString();
            minutesBox.textContent = jsObj.minutes.toString();
            
            date.textContent = jsObj.date.toString();
            dayBox.textContent = jsObj.day.toString();
        }
    };
    xhr.send();                
}, 10000);

function getEvents()
{
    while (events.firstChild)
    {
        events.removeChild(events.firstChild);
    }

    let xhr = new XMLHttpRequest();
    xhr.open("GET", "server.php?events");
    xhr.onreadystatechange = function()
    {
        if (xhr.readyState === 4 && xhr.status === 200)
        {
            let jsObj = JSON.parse(xhr.responseText);

            if (Object.keys(jsObj).length != 0)
            {
                events.removeAttribute("hidden");

                jsObj.forEach((element) => {
                    let event = document.createElement("div");
                    event.setAttribute("class", "event");
                    events.appendChild(event);
                    event.querySelector(".events:last-of-type");

                    let title = document.createElement("div");
                    title.setAttribute("class", "title");
                    let titleText = document.createTextNode(element.title_evnt);
                    title.appendChild(titleText);
                    event.appendChild(title);
                    let text = document.createElement("div");
                    text.setAttribute("class", "text");
                    let textText = document.createTextNode(element.text_evnt);
                    text.appendChild(textText);
                    event.appendChild(text);
                });
            }
            else
            {
                events.setAttribute("hidden", "true");
            }
        }
    };
    xhr.send();
}

getEvents();

setInterval(getEvents, 60000);
