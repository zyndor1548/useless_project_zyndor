document.getElementById('record_audio').addEventListener('click', async function () {
    const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
    const mediaRecorder = new MediaRecorder(stream);

    let recordedChunks = [];
    mediaRecorder.ondataavailable = event => {
        if (event.data.size > 0) recordedChunks.push(event.data);
    };

    mediaRecorder.onstop = async () => {
        const blob = new Blob(recordedChunks, { type: 'audio/wav' });

        let audioPlayer = document.getElementById('audio_player');
        if (!audioPlayer) {
            audioPlayer = document.createElement('audio');
            audioPlayer.id = 'audio_player';
            audioPlayer.controls = true;
            document.body.appendChild(audioPlayer);
        }
        audioPlayer.src = URL.createObjectURL(blob);

        const formData = new FormData();
        formData.append('audio', blob, 'recorded.wav');

        document.getElementById("status").textContent = 'Uploading...';

        try {
            const response = await fetch('./sound.php', {
                method: 'POST',
                body: formData
            });
            const result = await response.json();
            if (result.error) {
                document.getElementById("status").textContent = `Error: ${result.error}`;
            } else {
                document.getElementById("status").textContent = `Label: ${result.label}, Time: ${result.time}`;
            }
        } catch (e) {
            document.getElementById("status").textContent = `Error: ${e.message}`;
        }
    };

    mediaRecorder.start();
    setTimeout(() => mediaRecorder.stop(), 5000); // Record for 5 seconds
});

document.getElementById('upload_debug_audio').addEventListener('click', async function () {
    const fileInput = document.getElementById('debug_audio');
    const file = fileInput.files[0];
    if (!file) {
        document.getElementById("status").textContent = 'No file selected.';
        return;
    }

    let audioPlayer = document.getElementById('audio_player');
    if (!audioPlayer) {
        audioPlayer = document.createElement('audio');
        audioPlayer.id = 'audio_player';
        audioPlayer.controls = true;
        document.body.appendChild(audioPlayer);
    }
    audioPlayer.src = URL.createObjectURL(file);

    const formData = new FormData();
    formData.append('audio', file, file.name);

    document.getElementById("status").textContent = 'Uploading debug audio...';

    try {
        const response = await fetch('./sound.php', {
            method: 'POST',
            body: formData
        });
        const result = await response.json();
        if (result.error) {
            document.getElementById("status").textContent = `Error: ${result.error}`;
        } else {
            document.getElementById("status").textContent = `Label: ${result.label}, Time: ${result.time}`;
        }
    } catch (e) {
        document.getElementById("status").textContent = `Error: ${e.message}`;
    }
});