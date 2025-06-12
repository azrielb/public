#include "HuffmanNode.h"
using namespace std;

HuffmanNode::HuffmanNode(char t, unsigned f) : text(t), frequency(f), left(NULL), right(NULL) { 
}
HuffmanNode::HuffmanNode(HuffmanNode * l, HuffmanNode * r) : text(NULL), frequency(l->frequency + r->frequency), left(l), right(r) { 
}
HuffmanNode::~HuffmanNode()
{
	if (left != NULL) {
		delete left;
		delete right;
	}
}

HuffmanNode * HuffmanNode::getLeft() {
	return left;
}
HuffmanNode * HuffmanNode::getRight() {
	return right;
}
char HuffmanNode::getLetter() {
	return left != NULL ? NULL : text;
}
unsigned HuffmanNode::getFrequency() {
	return frequency;
}

bool HuffmanNode::operator==(const HuffmanNode & other) const { return frequency == other.frequency; }
bool HuffmanNode::operator!=(const HuffmanNode & other) const { return frequency != other.frequency; }
bool HuffmanNode::operator< (const HuffmanNode & other) const { return frequency <  other.frequency; }
bool HuffmanNode::operator> (const HuffmanNode & other) const { return frequency >  other.frequency; }
bool HuffmanNode::operator<=(const HuffmanNode & other) const { return frequency <= other.frequency; }
bool HuffmanNode::operator>=(const HuffmanNode & other) const { return frequency >= other.frequency; }
