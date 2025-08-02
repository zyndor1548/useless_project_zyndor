import { Client } from "https://esm.sh/@gradio/client";

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
        } else {
            console.log(result.data);
        }
    } catch (err) {
        console.error("Error:", err.message);
    }
}
document.getElementById('record_audio').addEventListener('click', async function () {
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

document.getElementById('upload_debug_audio').addEventListener('click', async function () {
    const fileInput = document.getElementById('debug_audio');
    const file = fileInput.files[0];
    if (!file) {
        console.log('No file selected.');
        return;
    }
    await classifyAudio(file);
});