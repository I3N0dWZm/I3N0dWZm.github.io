### Create a Media Player Core for Retroarch
### 08-09-24

I wanted to see if its possbile to generate my own core for retroarch to play a playlist of mp4's straight from the retroarch interface, as yes it is possible, if a bit clunky.
This was created in Xubuntu 24.01

This core uses VLC player but the command in "retro_load_game" could be changed to any media player (make sure vlc player is installed first).

[File: simple_external_player_libretro.so ](https://i3n0dwzm.github.io/Files/simple_external_player_libretro.so)

Here is how the new core was built.

#### Install the essential tools
```text
sudo apt install build-essential git
sudo apt install make
sudo apt install ffmpeg
sudo apt install vlc
sudo apt install pkg-config libasound2-dev libpulse-dev libx11-dev libudev-dev libgl1-mesa-dev libsdl2-dev
```
#### Grab the libretro.h file from the include directory in libretro-common
```text
mkdir ~/core
cd ~/core
git clone https://github.com/libretro/libretro-common.git
cp  ~/core/libretro-common/include/libretro.h ~/core/
```
#### Create libretro.c in the core folder
```c
#include <libretro.h>
#include <stdbool.h>
#include <stdio.h>
#include <stdlib.h>
#include <string.h>

static retro_environment_t environ_cb;

void retro_set_environment(retro_environment_t cb) {
    environ_cb = cb;

    bool no_game = true;
    cb(RETRO_ENVIRONMENT_SET_SUPPORT_NO_GAME, &no_game);
}

unsigned retro_api_version(void) {
    return RETRO_API_VERSION;
}

void retro_init(void) {
    // Core initialization
}

void retro_deinit(void) {
    // Core cleanup
}

void retro_set_video_refresh(retro_video_refresh_t cb) { }
void retro_set_audio_sample(retro_audio_sample_t cb) { }
void retro_set_audio_sample_batch(retro_audio_sample_batch_t cb) { }
void retro_set_input_poll(retro_input_poll_t cb) { }
void retro_set_input_state(retro_input_state_t cb) { }

void retro_get_system_info(struct retro_system_info *info) {
    if (!info)
        return;
    
    info->library_name = "Simple External Player";
    info->library_version = "v1.0";
    info->valid_extensions = "txt|m3u";  // Supported file extensions
    info->need_fullpath = true;          // Requires full path to the file
    info->block_extract = false;
}

void retro_get_system_av_info(struct retro_system_av_info *info) {
    memset(info, 0, sizeof(*info));

    info->timing.fps = 60.0;           // Basic timing info
    info->timing.sample_rate = 44100.0; // Basic audio rate

    info->geometry.base_width = 0;     // No video output
    info->geometry.base_height = 0;
    info->geometry.max_width = 0;
    info->geometry.max_height = 0;
    info->geometry.aspect_ratio = 0.0;
}

bool retro_load_game(const struct retro_game_info *game) {
    if (!game || !game->path) {
        return false;
    }

    // Execute external player with the file path
    char command[512];
    //snprintf(command, sizeof(command), "ffplay -f concat -safe 0 -i '%s' -autoexit -fs", game->path);
    snprintf(command, sizeof(command), "vlc --play-and-exit --fullscreen '%s'", game->path);

    int result = system(command);

    // Check if the command failed
    if (result != 0) {
        fprintf(stderr, "Error: Failed to execute command '%s'\n", command);
        return false;
    }

    return true;
}

bool retro_load_game_special(unsigned game_type, const struct retro_game_info *info, size_t info_size) {
    // Handle special game loading (if needed), otherwise use default implementation
    return retro_load_game(info);
}

void retro_run(void) {
    // Minimal run implementation (not doing anything here)
}

void retro_unload_game(void) {
    // Nothing to unload
}

unsigned retro_get_region(void) {
    return RETRO_REGION_NTSC;
}

bool retro_serialize(void *data, size_t size) {
    return false;
}

bool retro_unserialize(const void *data, size_t size) {
    return false;
}

size_t retro_serialize_size(void) {
    return 0; // Return 0 if serialization size is not applicable
}

void retro_cheat_reset(void) { }
void retro_cheat_set(unsigned index, bool enabled, const char *code) { }

void retro_set_controller_port_device(unsigned port, unsigned device) {
    // This core does not use input devices, so you can ignore this or provide minimal handling
}

void retro_reset(void) {
    // Basic reset implementation, can be empty if not used
}

void *retro_get_memory_data(unsigned id) {
    // Return NULL or handle specific memory IDs if needed
    return NULL;
}

size_t retro_get_memory_size(unsigned id) {
    // Return 0 or appropriate size if memory areas are used
    return 0;
}

```
#### Create Makefile in same folder
```text
CORE_NAME := simple_external_player

LIBRETRO_DIR := $(shell pwd)

GCC = gcc

CFLAGS = -fPIC -O3 -g -Wall -I$(LIBRETRO_DIR) -D__LIBRETRO__
LDFLAGS = -shared

OBJ = libretro.o

%.o: %.c
	$(GCC) $(CFLAGS) -c $< -o $@

all: $(CORE_NAME)_libretro.so

$(CORE_NAME)_libretro.so: $(OBJ)
	$(GCC) $(LDFLAGS) -o $@ $(OBJ)

clean:
	rm -f *.o $(CORE_NAME)_libretro.so
```
#### Build .so file and copy to core folder
```text
make clean
make
cp simple_external_player_libretro.so ~/.config/retroarch/cores/
```

#### Create an .info file about the core to help with assiocations
```text
cd /usr/share/libretro/info
sudo nano simple_external_player_libretro.info

display_name = "Simple External Player"
authors = "Me (and chatgpt!)"
supported_extensions = "txt|m3u"
corename = "Simple External Player"
manufacturer = "Custom"
categories = "Utilities"
systemname = "None"
license = "GPLv2"
permissions = ""
display_version = "v1.0"
supports_no_game = "true"
```

#### Generate a playlist (for vlc) in the folder with the mp4 files
```text
nano videos.m3u
....
file_example_MP4_480_1_5MG.mp4
sample-5s.mp4
....
```

#### Generate a playlist for retroarch to find this or multiple playlists

The example below calls the video m3u list with path, assined the core, the core used will pass the playlist to vlc.
replace <user> with the username, update paths to correct locations.
```text
nano music.lpl

{
  "version": "1.5",
  "default_core_path": "",
  "default_core_name": "",
  "label_display_mode": 0,
  "right_thumbnail_mode": 0,
  "left_thumbnail_mode": 0,
  "thumbnail_match_mode": 0,
  "sort_mode": 0,
  "items": [
    {
      "path": "/storage/music/videos.m3u",
      "label": "music list",
      "core_path": "/home/<user>/.config/retroarch/cores/simple_external_player_libretro.so",
      "core_name": "Simple External Player",
      "crc32": "",
      "db_name": "music.lpl"
    }
  ]
}
```
![alt text](https://i3n0dwzm.github.io/images/retroarch-video.png)
![alt text](https://i3n0dwzm.github.io/images/retroarch-video2.png)










