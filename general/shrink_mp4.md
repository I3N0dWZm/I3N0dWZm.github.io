### Quick Video Shrink Command
### 12-04-23

Modern smart phones have such a high resolution by default, this can cause an issue when attempting to email a video to someone.

In an ideal world you would of course just reduce the resolution on the phone, but should you have a very large video file, you can reduce the quality with ffmpeg to make it more manageable.

The below command uses ffmpeg to reduce the overall size with scale -2 keeps aspect of ratio, encode with libx265 and crf 24 (which is middle of the road compression).

```text
ffmpeg -i in.mp4 -vf scale=720:-2 -vcodec libx265 -crf 24 out.mp4

ffmpeg -i in.mp4 -vf scale=-2:720 -vcodec libx265 -crf 24 out.mp4
```
