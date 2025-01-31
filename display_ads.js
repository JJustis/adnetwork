function displayAd(ad) {
    const adContainer = document.getElementById("ad-container");
    adContainer.innerHTML = ""; // Clear previous ad

    const img = document.createElement("img");
    img.src = ad.image_url;
    img.style.width = "100%";
    img.style.height = "100%";
    img.style.opacity = "1";
    img.style.transition = "opacity 0.2s ease-in-out";

    adContainer.appendChild(img);

    // If there's a video, handle transition
    if (ad.video_url) {
        setTimeout(() => {
            img.style.opacity = "0"; // Start fading out
            setTimeout(() => {
                adContainer.removeChild(img); // Remove image after fade-out
                const video = document.createElement("video");
                video.src = ad.video_url;
                video.style.width = "100%";
                video.style.height = "100%";
                video.autoplay = true;
                video.controls = false;
                adContainer.appendChild(video);
            }, 200); // Wait for fade-out duration
        }, 100); // Show image for 0.1s
    }
}
