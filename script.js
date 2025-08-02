import { Client } from "https://esm.sh/@gradio/client";

function showPopup(message) {
    let popup = document.createElement('div');
    popup.id = 'mic-popup';
    popup.style.position = 'fixed';
    popup.style.top = '50%';
    popup.style.left = '50%';
    popup.style.transform = 'translate(-50%, -50%)';
    popup.style.background = '#ACEEBA';
    popup.style.color = '#fff';
    popup.style.padding = '24px 32px';
    popup.style.borderRadius = '16px';
    popup.style.boxShadow = '0 2px 8px rgba(0,0,0,0.15)';
    popup.style.zIndex = '9999';
    popup.style.fontSize = '20px';
    popup.innerText = message;
    document.body.appendChild(popup);
    document.getElementById("fade").style.display = "block";
}

function hidePopup() {
    const popup = document.getElementById('mic-popup');
    if (popup) popup.remove();
    document.getElementById("fade").style.display = "none";
}

async function reloadFeed() {
    hidePopup();
    const feed = document.querySelector('.feed-container');
    const response = await fetch('get_messages.php');
    const html = await response.text();
    feed.innerHTML = html;
}

async function classifyAudio(file) {
    try {
        const client = await Client.connect("ardneebwar/animals-sounds-classifier");
        const result = await client.predict("/predict", { filepath: file });


        let label = null;
        if (result.data && result.data.label) {
            label = result.data.label;
        } else if (Array.isArray(result.data) && result.data[0]?.label) {
            label = result.data[0].label;
        }

        if (label) {
            console.log(label)

    
            const response = await fetch('text.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `animal=${encodeURIComponent(label)}`
            });
            const text = await response.text();
            console.log(text);

            reloadFeed();
        } else {
            console.log(result.data);
        }
    } catch (err) {
        console.error("Error:", err.message);
    }
}
document.getElementById('record_audio').addEventListener('click', async function () {
    showPopup("Recording audio...");
    const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
    const mediaRecorder = new MediaRecorder(stream);

    let recordedChunks = [];
    mediaRecorder.ondataavailable = event => {
        if (event.data.size > 0) recordedChunks.push(event.data);
    };

    mediaRecorder.onstop = async () => {
        const blob = new Blob(recordedChunks, { type: 'audio/wav' });
        await classifyAudio(blob);
    };

    mediaRecorder.start();
    setTimeout(() => mediaRecorder.stop(), 5000)
});