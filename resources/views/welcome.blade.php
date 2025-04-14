<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>This is my website App</title>
  <link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
    rel="stylesheet"
  />
</head>
<body class="bg-gray text-black">
  <div class="container py-5">
    <h1 class="text-center text-success mb-4">This is my website App</h1>

    <!-- Search Form -->
    <form action="{{ route('home') }}" method="GET" class="mb-5">
      <div class="input-group mx-auto" style="max-width: 600px; height: 50px;">
        <input
          type="text"
          name="query"
          class="form-control bg-secondary text-white border-0"
          placeholder="Search for a song, artist, or album"
          style="border-radius: 50px 0 0 50px;"
        />
        <button type="submit" class="btn btn-success" style="border-radius: 0 50px 50px 0;">
          Search
        </button>
      </div>
    </form>
    

    <!-- Content Row -->
    <div class="row">
      <!-- Left Side: Music List -->
      <div class="col-md-7">
        <div class="list-group">
          @foreach($tracks as $track)
            @php
              $trackData = $track['track'] ?? $track;
              $trackId = $trackData['id'] ?? '';
              $trackName = $trackData['name'] ?? 'Unknown Track';
              $artistName = $trackData['artists'][0]['name'] ?? 'Unknown Artist';
              $albumName = $trackData['album']['name'] ?? 'Unknown Album';
              $albumImage = $trackData['album']['images'][0]['url'] ?? '';
            @endphp
            <a
              href="#"
              class="track-link list-group-item list-group-item-action bg-secondary text-black border-0 d-flex align-items-center mb-2 rounded shadow-sm" 
              data-track="{{ $trackId }}"
            >
              {{-- <img
                src="{{ $albumImage }}"
                alt="Album Cover"
                class="me-3 rounded"
                style="width: 60px; height: 60px;"
              /> --}}
              <div>
                <h5 class="mb-1">{{ $trackName }}</h5>
                <p class="mb-0 text-light small">
                  {{ $artistName }} • {{ $albumName }}
                </p>
              </div>
            </a>
          @endforeach
        </div>
      </div>

      <!-- Right Side: Spotify Embed Player -->
      <div class="col-md-5 mt-4 mt-md-0">
        <div class="card  border-0 ">
          <iframe
            id="spotify-player"
            class="rounded"
            style="width: 100%; height: 400px; border-radius: 20%;"
            frameborder="0"
            allow="autoplay; encrypted-media; clipboard-write;"
            src="https://open.spotify.com/embed/track/{{ $defaultTrackId ?? '7ouMYWpwJ422jRcDASZB7P' }}?utm_source=generator"
          ></iframe>
        </div>
      </div>
    </div>
  </div>

  <script>
    // Click on song profile to play
    document.querySelectorAll('.track-link').forEach(link => {
      link.addEventListener('click', function (event) {
        event.preventDefault();
        let trackId = this.getAttribute('data-track');
        let player = document.getElementById('spotify-player');
        if (trackId) {
          player.src = `https://open.spotify.com/embed/track/${trackId}?utm_source=generator`;
        } else {
          alert("Track ID not found");
        }
      });
    });
  </script>
</body>
</html>
