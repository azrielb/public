"""
Circular Cake Flip Simulator
"""
import math

TWO_PI = 2 * math.pi
START = [(0, TWO_PI)]


def print_segments(segments):
    print(",".join(f"{s:.2f}->{e:.2f}" for s, e in segments))


def print_segments_colors(segments):
    print(",".join("B" if s > e else "W" for s, e in segments))


def invert_single_range(segments, flip_start, flip_end):
    """
    Flip values in POSITION range [flip_start, flip_end].
    Segments are (start_val, end_val) tuples covering consecutive positions.
    """
    new_segments = []
    pos = 0  # Current position

    for seg_start, seg_end in segments:
        seg_len = abs(seg_end - seg_start)
        seg_pos_start = pos
        seg_pos_end = pos + seg_len

        # Check if segment overlaps with flip range
        if seg_pos_end <= flip_start or seg_pos_start >= flip_end:
            # No overlap
            new_segments.append((seg_start, seg_end))
        else:
            is_increasing = seg_start < seg_end

            # Value at a position within the segment
            def value_at_pos(p):
                if is_increasing:
                    return seg_start + (p - seg_pos_start)
                else:
                    return seg_start - (p - seg_pos_start)

            # Part before flip range
            if seg_pos_start < flip_start:
                val_at_flip_start = value_at_pos(flip_start)
                new_segments.append((seg_start, val_at_flip_start))

            # Flipped part
            overlap_start = max(seg_pos_start, flip_start)
            overlap_end = min(seg_pos_end, flip_end)
            val_at_overlap_start = value_at_pos(overlap_start)
            val_at_overlap_end = value_at_pos(overlap_end)
            new_segments.append((val_at_overlap_end, val_at_overlap_start))

            # Part after flip range
            if seg_pos_end > flip_end:
                val_at_flip_end = value_at_pos(flip_end)
                new_segments.append((val_at_flip_end, seg_end))

        pos = seg_pos_end

    return new_segments


def invert_segment(segments, start_pos, length):
    """
    Flip (reverse) a segment of the cake, with circular wrapping.

    Args:
        segments: List of (start, end) tuples
        start_pos: Starting position of the flip
        length: Length of segment to flip

    Returns:
        Tuple of (new segments, flip_end_position)
    """
    end_pos = start_pos + length

    if end_pos <= TWO_PI:
        # No wrapping needed
        return invert_single_range(segments, start_pos, end_pos), end_pos
    else:
        # Wrapping: flip [start_pos, TWO_PI] and [0, end_pos - TWO_PI]
        segments = invert_single_range(segments, start_pos, TWO_PI)
        wrap_end = end_pos - TWO_PI
        segments = invert_single_range(segments, 0, wrap_end)
        return segments, wrap_end


def main():
    import sys

    # Usage: python cake_cuts.py [mode] [length] [iterations]
    # mode: n/numbers, c/colors, both (default: both)
    # length: flip length (default: 3)
    # iterations: number of flips (default: 5)

    mode = "n"
    length = 3
    iterations = 15

    if len(sys.argv) > 1:
        mode = sys.argv[1].lower()
    if len(sys.argv) > 2:
        length = float(sys.argv[2])
    if len(sys.argv) > 3:
        iterations = int(sys.argv[3])

    def format_segments(segments, flip_end=None):
        """Format segments with a space after the flip end position."""
        parts = []
        pos = 0
        for s, e in segments:
            seg_len = abs(e - s)
            seg_end_pos = pos + seg_len
            parts.append((s, e, abs(seg_end_pos - flip_end) < 0.001 if flip_end is not None else False))
            pos = seg_end_pos
        return parts

    def print_output(line_num, segments, flip_end=None):
        prefix = f"{line_num:2d}: "
        parts = format_segments(segments, flip_end)

        if mode == "numbers" or mode == "n":
            result = []
            for i, (s, e, add_space) in enumerate(parts):
                sep = ", " if add_space else ","
                result.append(f"{s:.2f}->{e:.2f}")
                if i < len(parts) - 1:
                    result.append(sep)
            print(prefix + "".join(result))
        elif mode == "colors" or mode == "c":
            result = []
            for i, (s, e, add_space) in enumerate(parts):
                sep = ", " if add_space else ","
                result.append("B" if s > e else "W")
                if i < len(parts) - 1:
                    result.append(sep)
            print(prefix + "".join(result))
        else:  # both
            result_n = []
            result_c = []
            for i, (s, e, add_space) in enumerate(parts):
                sep = ", " if add_space else ","
                result_n.append(f"{s:.2f}->{e:.2f}")
                result_c.append("B" if s > e else "W")
                if i < len(parts) - 1:
                    result_n.append(sep)
                    result_c.append(sep)
            print(prefix + "".join(result_n))
            print("    " + "".join(result_c))
            print()

    segments = START
    start = 0
    print_output(0, segments)
    for i in range(iterations):
        segments, flip_end = invert_segment(segments, start, length)
        print_output(i + 1, segments, flip_end)
        start += length
        start %= TWO_PI


if __name__ == "__main__":
    main()
