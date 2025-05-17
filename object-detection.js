async function setupCamera() {
    const video = document.getElementById('video');
    try {
        const stream = await navigator.mediaDevices.getUserMedia({ video: true });
        video.srcObject = stream;
        await new Promise(resolve => (video.onloadedmetadata = resolve));
    } catch (error) {
        console.error("Error accessing camera:", error);
    }
}
async function detectObjects() {
    const video = document.getElementById('video');
    const model = await cocoSsd.load();
    console.log("COCO-SSD Model Loaded!");

    async function detect() {
        if (!video.videoWidth || !video.videoHeight) {
            requestAnimationFrame(detect);
            return;
        }

        const predictions = await model.detect(video);
        
        // Filter for furniture-related objects
        const furniture = predictions.filter(pred => 
            ["chair", "couch", "bed", "tv", "table"].includes(pred.class)
        );

        drawBoundingBoxes(furniture);
        showARModels(furniture);
        
        requestAnimationFrame(detect);
    }

    detect();
}
function drawBoundingBoxes(predictions) {
    const canvas = document.getElementById('canvas');
    const ctx = canvas.getContext('2d');
    const video = document.getElementById('video');

    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;
    ctx.clearRect(0, 0, canvas.width, canvas.height);

    predictions.forEach(prediction => {
        const [x, y, width, height] = prediction.bbox;
        ctx.strokeStyle = "blue";
        ctx.lineWidth = 2;
        ctx.strokeRect(x, y, width, height);
    });
}

function showARModels(predictions) {
    const scene = document.getElementById("ar-scene");

    // Clear previous AR models
    while (scene.firstChild) {
        scene.removeChild(scene.firstChild);
    }

    predictions.forEach((prediction, index) => {
        const detectedFurniture = prediction.class;
        let suggestedItems = [];

        // Suggested 3D models (Use actual .glb models)
        switch (detectedFurniture) {
            case "chair":
                suggestedItems = ["chair.glb", "lamp.glb", "carpet.glb"];
                break;
            case "table":
                suggestedItems = ["chair.glb", "vase.glb", "lamp.glb"];
                break;
            case "bed":
                suggestedItems = ["side-table.glb", "lamp.glb", "painting.glb"];
                break;
            case "couch":
                suggestedItems = ["coffee-table.glb", "rug.glb", "painting.glb"];
                break;
            case "tv":
                suggestedItems = ["tv-stand.glb", "shelf.glb", "sound-system.glb"];
                break;
        }

        // Place models in AR scene dynamically
        suggestedItems.forEach((model, i) => {
            const entity = document.createElement("a-entity");
            entity.setAttribute("gltf-model", `models/${model}`);
            entity.setAttribute("position", `${index * 2} 0 -3`); // Spread models evenly
            entity.setAttribute("scale", "1 1 1");
            scene.appendChild(entity);
            console.log(`Added AR Model: ${model}`);
        });
    });
}


window.onload = async () => {
    await setupCamera();
    await detectObjects();
};
