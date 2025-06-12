using System;
using System.IO;

namespace AzrielClasses
{
    public static class AzrielFunctions
    {
        public static string nullToEmptyString(this string str)
        {
            if (str == null) str = "";
            return str;
        }
        public static void SwapWith<T>(this T a, ref T b)
        {
            T temp = a;
            a = b;
            b = temp;
        }
        public static void Swap<T>(ref T a, ref T b)
        {
            T temp = a;
            a = b;
            b = temp;
        }
        public static void division(ref double big, ref double small, double div)
        {
            double temp = small % div;
            big += (small - temp) / div;
            if (temp < 0)
            {
                --big;
                small = div + temp;
            }
            else
            {
                small = temp;
            }
        }
        public static void multiply(ref double big, ref double small, double mul)
        {
            small += (big % 1) * mul;
            big = Math.Floor(big);
        }
        public static string short_str(string str, int maxLength = 23, bool end = false)
        {
            if (str.nullToEmptyString().Length <= maxLength || (end && str.Length <= maxLength * 2 + 5))
                return str;
            int a = str.Substring(0, maxLength).LastIndexOf(' ');
            string start = str.Substring(0, a < 0 ? maxLength - 1 : a) + " ... ";
            if (end)
                start += str.Substring(str.IndexOf(' ', str.Length - maxLength) + 1 - maxLength);
            return start;
        }
        public static string make_file_name(string filename, string address = null)
        {
            string ext, add = "";
            int point = filename.nullToEmptyString().StartsWith(".") ? 0 : filename.LastIndexOf('.');
            if (point < 0)
            {
                ext = null;
            }
            else
            {
                ext = filename.Substring(point);
                filename = filename.Substring(0, point);
            }
            if (filename.Length == 0) filename = "a";
            if (address.nullToEmptyString().Length >= 0) address += '/';
            for (char a = 'a'; a <= 'z' && File.Exists(address + filename + add + ext); ++a)
                add = new string(a, 1);
            for (char a = 'a'; a <= 'z' && File.Exists(address + filename + add + ext); ++a)
                if (!File.Exists(address + filename + a + 'z' + ext))
                    for (char b = 'a'; b <= 'z' && File.Exists(address + filename + add + ext); ++b)
                        add = new string(new char[] { a, b });
            for (char a = 'a'; a <= 'z' && File.Exists(address + filename + add + ext); ++a)
                if (!File.Exists(address + filename + a + "zz" + ext))
                    for (char b = 'a'; b <= 'z' && File.Exists(address + filename + add + ext); ++b)
                        if (!File.Exists(address + filename + a + b + 'z' + ext))
                            for (char c = 'a'; c <= 'z' && File.Exists(address + filename + add + ext); ++c)
                                add = new string(new char[] { a, b, c });
            return File.Exists(address + filename + add + ext) ? "" : filename + add + ext;
        }

        public static double[] test_time<T>(Action<T>[] funcs, T parameter, int times = 1000000)
        {
            int funcsNum = funcs.Length;
            double[] theTimes = new double[funcsNum];
            for (int f = 0; f < funcsNum; ++f)
            {
                long Ticks = -DateTime.Now.Ticks;
                for (int i = 0; i < times; ++i)
                    funcs[f](parameter);
                Ticks += DateTime.Now.Ticks;
                theTimes[f] = Ticks / TimeSpan.TicksPerMillisecond;
            }
            return theTimes;
        }

        public static double[] gettimeSMH(double seconds = 0, double minutes = 0, double hours = 0)
        {
            double time = seconds + (minutes + hours * 60) * 60;
            //sgn = time / abs(time);
            time = Math.Abs(time);
            hours = (int)(time / 3600);
            minutes = (int)(time / 60) % 60;
            seconds = time - (int)(time / 60) * 60;
            return new double[] { time, hours, minutes, seconds };
        }
    }
}
