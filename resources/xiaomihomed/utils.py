def hex_color_to_rgb(color):
	"Convert a hex color string to an RGB tuple."
	color = color.strip("#")
	try:
		red, green, blue = tuple(int(color[i:i + 2], 16) for i in (0, 2, 4))
	except:
		red, green, blue = (255, 0, 0)
	return red, green, blue
