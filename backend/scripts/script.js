window.onload = async function () {
  window.location.href = "#preloader";
  await botResponse();
  window.location.href = "#";

  const inputElement = document.getElementById("textAreaExample");

  inputElement.addEventListener("keyup", function (event) {
    if (event.key === "Enter") {
      sendMessage();
    }
  });
};

async function sendMessage(msj) {
  let Message = document.getElementById("textAreaExample").value;
  console.log(Message, msj);
  if (Message === "" && msj) {
    console.log(10);
    Message = msj;
    const MessageDiv = `
      <div class="fade-in-right d-flex flex-row justify-content-end mb-4">
        <div class="p-3 me-3 border" style="border-radius: 15px; background-color: #008be11c;">
          <p class="small mb-0">${Message}</p>
        </div>
        <img src="user.png" alt="avatar 1" style="width: 30px !important;     border-radius: 50%;">
      </div>
    `;
    const card_body = document.getElementById("card-body");
    card_body.insertAdjacentHTML("beforeend", MessageDiv);
    document.getElementById("textAreaExample").value = "";
    card_body.scrollTop = card_body.scrollHeight;
  } else {
    if (Message !== "") {
      console.log(20);
      const MessageDiv = `
        <div class="fade-in-right d-flex flex-row justify-content-end mb-4">
          <div class="p-3 me-3 border" style="border-radius: 15px; background-color: #008be11c;">
            <p class="small mb-0">${Message}</p>
          </div>
          <img src="user.png" alt="avatar 1" style="width: 30px !important;     border-radius: 50%;">
        </div>
      `;
      const card_body = document.getElementById("card-body");
      card_body.insertAdjacentHTML("beforeend", MessageDiv);
      document.getElementById("textAreaExample").value = "";
      card_body.scrollTop = card_body.scrollHeight;
      botResponse(Message);
    }
  }
}

let messageQueue = [];
let isQueueActive = false;

async function addToQueue(message, htmlBtn = false) {
  if (htmlBtn) {
    const buttonArray = JSON.parse(message);
    const randomId = await generateRandomId();
    const buttonsHTML = buttonArray
      .map(
        (button) =>
          `<button type="button" class="btn btn-outline-primary mb-1 ${randomId}" onclick="btnOption(${button.order}, ${button.state}, '${randomId}', '${button.msj}')">${button.msj}</button>`
      )
      .join(" ");
    message = buttonsHTML;
  }
  messageQueue.push(message);
  if (!isQueueActive) {
    isQueueActive = true;
    processQueue();
  }
}

async function processQueue() {
  if (messageQueue.length === 0) {
    isQueueActive = false;
    return;
  }
  const nextMessage = messageQueue.shift();
  await showMessage(nextMessage);
  if (messageQueue.length > 0) {
    await new Promise((resolve) => setTimeout(resolve, 1500));
    processQueue();
  }
}

async function showMessage(message) {
  try {
    let miDiv = document.getElementById("btn_submit");
    miDiv.style.pointerEvents = "auto";
    const inputElement = document.getElementById("textAreaExample");
    inputElement.readOnly = false;
    inputElement.value = "";
    inputElement.focus();
    const randomId = await generateRandomId();
    const messageDiv = await createMessageDiv(randomId, message);
    const cardBody = document.getElementById("card-body");
    cardBody.insertAdjacentHTML("beforeend", messageDiv);
    cardBody.scrollTop = cardBody.scrollHeight;
    inputElement.focus();
    isQueueActive = false;
    setTimeout(() => {
      console.log(message);
      if (message.includes("</button>") || message.includes("</a>")) {
        console.log("ESTOY AQUI");
        inputElement.readOnly = true;
        miDiv.style.pointerEvents = "none";
      }
    }, 250);
  } catch (error) {
    console.error("Error displaying message:", error);
  }
}

async function createMessageDiv(id, message) {
  if (message.includes("</button>")) {
    return `
      <div class="fade-in-left d-flex flex-row justify-content-start mb-4">
        <img src="caro.jpeg" alt="avatar 1" style="width: 30px !important;     border-radius: 50%;">
        <div class="p-3 ms-3" style="border-radius: 15px; background-color: transparent !important">
          <div id="${id}" class="btn-box">${message}</div>
        </div>
      </div>
    `;
  }
  return `
    <div class="fade-in-left d-flex flex-row justify-content-start mb-4">
      <img src="caro.jpeg" alt="avatar 1" style="width: 30px !important;     border-radius: 50%;">
      <div class="p-3 ms-3" style="border-radius: 15px; background-color: #e3e3e3 !important">
        <p class="small mb-0 typing anim-typewrite" id="${id}">${message}</p>
      </div>
    </div>
  `;
}

async function generateRandomId() {
  return Math.random().toString(36).substr(2, 8);
}

const BOT_STATES = {
  INITIAL: 0,
};

let state = BOT_STATES.INITIAL;

async function botResponse(Message) {
  switch (state) {
    case BOT_STATES.INITIAL:
      await handleConversation(Message);
      break;
  }
}

let conversation = [];

let index = 0;

async function handleConversation(Message) {
  const inputElement = document.getElementById("textAreaExample");
  inputElement.readOnly = true;

  if (!Message || Message === "") {
    Message = "¡Hola Carola, habla Joaquín!";
  }

  conversation.push({ text: `${Message}` });

  index = index + 1;

  console.log(index);

  let carolaResponse = await queryToCarola(conversation);

  conversation.push({ text: `${carolaResponse}` });

  await addToQueue(carolaResponse);

  console.log(conversation);

  inputElement.readOnly = false;

  speakWithParams(carolaResponse);
}

let voices = [];

voices = window.speechSynthesis.getVoices();

function speakWithParams(
  text,
  rate = 1.25,
  pitch = 1.5,
  volume = 1,
  lang = "es-MX",
  voiceIndex = 0
) {
  //console.log(window.speechSynthesis.getVoices());

  const utterance = new SpeechSynthesisUtterance(text);
  utterance.rate = rate;
  utterance.pitch = pitch;
  utterance.volume = volume;
  utterance.lang = lang;

  if (voices[voiceIndex]) {
    utterance.voice = voices[voiceIndex];
  }

  window.speechSynthesis.speak(utterance);
}

async function btnOption(order, state, randomId, msj) {
  let elementosConClase123 = document.getElementsByClassName(randomId);

  for (let i = 0; i < elementosConClase123.length; i++) {
    elementosConClase123[i].style.pointerEvents = "none";
  }

  botResponse(order);
}

async function queryToCarola(c) {
  if (!c || c === "") {
    throw new Error(
      "Parece ser que tu Mensaje esta vacio, ¿Que querias decirme?"
    );
  }

  const url = `backend/php/gemini.php?index=${index}`;
  const data = c;

  try {
    const response = await fetch(url, {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify(data),
    });

    const result = await response.json();

    console.log(result);

    if (!result.error) {
      let lastIndex = result.response.candidates[0].content.parts.length - 1;

      return result.response.candidates[0].content.parts[lastIndex].text;
    } else {
      throw new Error(result.error);
    }
  } catch (error) {
    console.log(error);
    return error.toString();
  }
}
